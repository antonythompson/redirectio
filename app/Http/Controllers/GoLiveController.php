<?php

namespace App\Http\Controllers;

use App\Jobs\GetWhmList;
use App\Jobs\TestWebsites;
use App\Website;
use Illuminate\Http\Request;

class GoLiveController extends Controller
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

        //GetWhmList::dispatchNow(); die();
        //TestWebsites::dispatchNow();

        $websites = Website::query()->limit(50)->get();

        return view('go-live', compact('websites'));
    }
}
