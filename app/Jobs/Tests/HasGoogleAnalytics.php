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

class HasGoogleAnalytics
{
    public static function test(Website $website, Test $test, $web_content, $server_info)
    {
        if (strpos($web_content, 'www.google-analytics.com/analytics.js')) {
            $test->google_analytics = true;
        } else if (strpos($web_content, 'https://www.googletagmanager.com/gtm.js')) {
            $test->google_analytics = true;
        }
        return $test;
    }
}