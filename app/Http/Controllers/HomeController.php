<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        // get team members
        $team = User::orderBy('admin', 'asc')
            ->orderBy('display_name', 'desc')
            ->where('display_enabled', 1)
            ->select('display_name', 'pronouns', 'avatar', 'desc_de', 'desc_en')
            ->get();

        return view('home', compact('events', 'team'));
    }

    /**
     * Show the Calendar Page
     */
    public function indexCal(int $eventId = null): View
    {
        // only show future events on homepage
        $events = $this->get_events(2, $eventId);

        return view('calendar', compact('events'));
    }
}
