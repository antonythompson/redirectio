<?php

namespace App\Http\Controllers;

use App\Jobs\GetWhmList;
use App\Jobs\TestWebsites;
use App\Website;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $websites = Website::get();
        $data = [];
        echo '<table>';
            echo '<tr>';
                echo '<td>Domain</td>';
                echo '<td>ssl</td>';
                echo '<td>ssl_redirect</td>';
                echo '<td>google_analytics</td>';
                echo '<td>ip_correct</td>';
            echo '</tr>';
        foreach ($websites as $website) {
            $latestTest = $website->latestTest();
            echo '<tr>';
            echo '<td>'.$website->domain.'</td>';
            echo '<td>'. ($latestTest->ssl ?? '') .'</td>';
            echo '<td>'. ($latestTest->ssl_redirect ?? '') .'</td>';
            echo '<td>'. ($latestTest->google_analytics ?? '') .'</td>';
            echo '<td>'. ($latestTest->ip_correct ?? '') .'</td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
//        TestWebsites::dispatch();
        return view('home');
    }
}
