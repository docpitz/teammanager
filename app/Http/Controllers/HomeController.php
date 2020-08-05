<?php

namespace App\Http\Controllers;

use App\Buisness\Enum\PermissionEnum;
use App\Event;
use Illuminate\Support\Facades\Gate;

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

        $countFutureQuietEvents = Gate::check(PermissionEnum::getInstance(PermissionEnum::EventBookingImmediate)->key) ? $user->countFutureQuietEvents() : $user->countFutureQuietEventsDelayed();
        $countFuturePromisedAndWaitlistEvents = $user->countFuturePromisedAndWaitlistEvents();
        $futureEvents = Event::overviewForUser($user->id)->get();

        return view('home.index', ['countFutureQuietEvents' => $countFutureQuietEvents, 'countFuturePromisedAndWaitlistEvents' => $countFuturePromisedAndWaitlistEvents, 'futureEvents' => $futureEvents]);
    }
}
