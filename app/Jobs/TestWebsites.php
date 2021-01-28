<?php

namespace App\Jobs;

use App\Ip;
use App\Jobs\Tests\HasGoogleAnalytics;
use App\Jobs\Tests\HasIPCorrect;
use App\Jobs\Tests\HasSSL;
use App\Jobs\Tests\HasSSLRedirection;
use App\Server;
use App\Test;
use App\Website;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TestWebsites implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Client
     */
    private $client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->client = new Client();
        Website::query()->chunk(100, function ($websites) {

            foreach ($websites as $website) {

                $test = new Test();

                try{

                    $server_info = [];
                    $response = $this->client->get('http://'.$website->domain, [
                        'on_stats' => function ( TransferStats $stats ) use ( &$server_info ) {
                            $server_info['url'] = $stats->getEffectiveUri();
                            $server_info['handler_stats'] = $stats->getHandlerStats();
                        },
                        'verify' => false
                    ]);
                    $web_content = $response->getBody()->getContents();

                    $test = HasSSL::test($website, $test, $web_content, $server_info);
                    $test = HasSSLRedirection::test($website, $test, $web_content, $server_info);
                    $test = HasIPCorrect::test($website, $test, $web_content, $server_info);
                    $test = HasGoogleAnalytics::test($website, $test, $web_content, $server_info);

                } catch (RequestException $e) {

                    if (! $e->hasResponse()) {
                        //No response from server. Assume the host is offline or server is overloaded.
                        $test->is_down = true;
                    }

                    //dd('RequestException', $e->getCode(), $e->getMessage(), $server_info);
                }

                $test->save();
                $website->tests()->attach($test);



//                // Get the number of redirects
//                echo $response->getRedirectCount();
//
//                // Iterate over each sent request and response
//                foreach ($history->getAll() as $transaction) {
//                    // Request object
//                    echo $transaction['request']->getUrl() . "\n";
//                    // Response object
//                    echo $transaction['response']->getEffectiveUrl() . "\n";
//                }
//                // Or, simply cast the HistoryPlugin to a string to view each request and response
//                echo $history;
            }
        });
    }
}
