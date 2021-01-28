<?php
/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 28/01/2021
 * Time: 4:39 PM
 */

namespace App\Jobs\Tests;


use App\Test;
use App\Website;
use GuzzleHttp\Client;

class HasSSL
{
    public static function test(Website $website, Test $test, $web_content, $server_info)
    {
//        $client = new Client();
//        $ssl_check = $client->get('https://'.$website->domain);
//        if ($ssl_check->getStatusCode() === 200) {
//            $test->ssl = true;
//        }
        $test->ssl = $server_info['handler_stats']['scheme'] == 'HTTPS';
        return $test;
    }
}