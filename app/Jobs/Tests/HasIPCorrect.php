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

class HasIPCorrect
{
    public static function test(Website $website, Test $test, $web_content, $server_info)
    {
        $test->ip_correct = $server_info['handler_stats']['primary_ip'] === $website->ip->ip;
        return $test;
    }
}