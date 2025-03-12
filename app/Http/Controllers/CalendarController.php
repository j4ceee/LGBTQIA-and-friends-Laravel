<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Get all events
     * @param int $subWeeks How many weeks back events should be included
     * @param int|null $event_id
     * @return Collection
     */
    public function get_events(int $subWeeks = 2, int $event_id = null): Collection
    {
        $events = [];

        if (!$event_id) { // not fetching specific event
            $start_time = now()->subWeeks($subWeeks)->startOfDay();

            $events = Event::where('date_start', '>=', $start_time)->get();
        }
        else {
            $events = Event::where('id', $event_id)->get();
        }
        return $events;
    }
}
