<?php

namespace App\Jobs;

use App\Ip;
use App\Server;
use App\Test;
use App\Website;
use Guzzle\Plugin\History\HistoryPlugin;
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
        Website::chunk(100, function ($websites) {
            foreach ($websites as $website) {
                $test = new Test([
                    'ssl' => false,
                    'ssl_redirect' => false,
                    'google_analytics' => false,
                    'ip_correct' => false,
                ]);

                try{
                    $rstats = [];
                    $response = $this->client->get('http://'.$website->domain, [
                        'on_stats' => function (TransferStats $stats) use (&$rstats) {
                            $rstats['url'] = $stats->getEffectiveUri();
                            $rstats['handler_stats'] = $stats->getHandlerStats();
                        },
                        'verify' => false
                    ]);
//                    dd($rstats);
                    $content = $response->getBody()->getContents();

                    if ($rstats['url']->getScheme() === 'https') {
                        $test->ssl = true;
                        $test->ssl_redirect = true;
                    } else {
                        $ssl_check = $this->client->get('https://'.$website->domain);
                        if ($ssl_check->getStatusCode() === 200) {
                            $test->ssl = true;
                        }
                    }
                    $test->ip_correct = $rstats['handler_stats']['primary_ip'] === $website->ip->ip;
                    if (strpos($content, 'www.google-analytics.com/analytics.js')) {
                        $test->google_analytics = true;
                    } else if (strpos($content, 'https://www.googletagmanager.com/gtm.js')) {
                        $test->google_analytics = true;
                    }
                } catch (RequestException $e) {
//                    dd('RequestException', $e->getCode(), $e->getMessage(), $rstats);
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
