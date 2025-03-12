<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends CalendarController
{
    /**
     * Show the application home
     */
    public function index(): View
    {
        // only show future events on homepage
        $events = $this->get_events(0, null);
        return view('home', ['events' => $events]);
    }
}
