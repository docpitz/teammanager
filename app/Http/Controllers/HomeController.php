<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $countFutureQuietEvents = $user->countFutureQuietEvents();
        $countFuturePromisedAndWaitlistEvents = $user->countFuturePromisedAndWaitlistEvents();
        return view('home.index', ['countFutureQuietEvents' => $countFutureQuietEvents, 'countFuturePromisedAndWaitlistEvents' => $countFuturePromisedAndWaitlistEvents]);
    }
}
