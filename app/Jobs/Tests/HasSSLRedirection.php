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

class HasSSLRedirection
{
    public static function test(Website $website, Test $test, $web_content, $server_info)
    {
        if ($server_info['url']->getScheme() === 'https') {
            $test->ssl_redirect = true;
        }
        return $test;
    }
}