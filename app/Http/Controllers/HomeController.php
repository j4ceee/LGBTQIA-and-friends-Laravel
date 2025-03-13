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
        $team = User::orderBy('display_name', 'asc')
            ->where('display_enabled', 1)
            ->select('display_name', 'pronouns', 'avatar', 'desc_de', 'desc_en')
            ->get();

        return view('home', ['events' => $events, 'team' => $team]);
    }
}
