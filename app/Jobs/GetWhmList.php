<?php

namespace App\Jobs;

use App\Ip;
use App\Server;
use App\Website;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetWhmList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accounts = $this->getAccounts();
        if ($accounts) {
            $this->processAccounts($accounts);
        }
    }

    private function processAccounts(Array $accounts)
    {
        Server::updateOrCreate([
            'name' => 'VPS City',
            'domain' => 'hosted.publicaddm.co.nz'
        ]);
        foreach ($accounts as $account) {
            $ip = Ip::updateOrCreate([
                'ip' => $account['ip'],
                'server_id' => 1
            ]);
            Website::updateOrCreate([
                'domain' => $account['domain'],
                'ip_id' => $ip->id
            ],[
                'active' => 1,
            ]);
        }
    }
    private function getAccounts()
    {

        $user = "root";
        $token = env('WHM_TOKEN');
        $query = "https://hosted.publicaddm.co.nz:2087/json-api/listaccts?api.version=1";
//        dd($user, $token);
        $client = new Client();

        $response = $client->get($query, [
            'headers' => [
                'Authorization' => "whm $user:$token"
            ]
        ]);
        $accounts = false;
        if ($response->getStatusCode() === 200) {
            $decode = json_decode($response->getBody(), true);
            $accounts = $decode['data']['acct'];
        }
        return $accounts;
    }
}
