<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class CalendarController extends Controller
{
    /**
     * Get all events in the last $subWeeks weeks or one specific event. Single event mode throws 404 if none found.
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

            if ($events == null || $events->isEmpty()) {
                abort(404);
            }
        }
        return $events;
    }
}
