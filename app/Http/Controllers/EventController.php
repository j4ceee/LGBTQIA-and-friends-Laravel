<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventLocation;
use App\Models\EventType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventController extends CalendarController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $event_locs = EventLocation::all();
        $event_types = EventType::all();

        return view('event.ev-create', compact('event_locs', 'event_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // cast enable_desc to boolean (check if it exists)
        $request->merge([
            'enable_desc' => $request->has('enable_desc'),
        ]);

        $this->validateEvent($request);

        return DB::transaction(function () use ($request) {
            // check if event type & location exist, if not, create them
            $type = $this->checkIfEventTypeExists($request->event_name_de, $request->event_name_en);
            $location = $this->checkIfLocationExists($request->event_location);
            $type_id = $type->id;
            $location_id = $location->id;

            // handle sequence number---------------------------------
            $event_seq = 1; // default sequence number

            // handle event time--------------------------------------
            $event_start = Carbon::createFromFormat('Y-m-d\TH:i', $request->event_date_start, 'Europe/Berlin')
                ->setTimezone('UTC');

            $event_end = Carbon::createFromFormat('Y-m-d\TH:i', $request->event_date_end, 'Europe/Berlin')
                ->setTimezone('UTC');

            // handle uid----------------------------------------------
            $event_uid = bin2hex(random_bytes(16)) . "-" . time() . "@lgbt-hs-ansbach.de";

            // handle description--------------------------------------
            list($desc_de, $desc_en) = $this->setDescription($request, $type);

            $event = new Event();
            $event->event_type_id = $type_id;
            $event->event_location_id = $location_id;
            $event->date_start = $event_start;
            $event->date_end = $event_end;
            $event->uid = $event_uid;
            $event->desc_de_override = $desc_de;
            $event->desc_en_override = $desc_en;
            $event->sequence = $event_seq;
            $event->save();
            $id = $event->id;

            $this->generateICS();

            return redirect()
                ->route('event.show', ['id' => $id])
                ->with('success', __('events.create_success'));
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $event_id): View
    {
        $event_locs = EventLocation::all();
        $event_types = EventType::all();

        $events = $this->get_events(0, $event_id);
        $event = $events->first();

        return view('event.ev-edit', compact('event_locs', 'event_types', 'event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $eventId)
    {
        $events = $this->get_events(0, $eventId); // get event (throws 404 if none found)
        $event = $events->first();

        // cast enable_desc to boolean (check if it exists)
        $request->merge([
            'enable_desc' => $request->has('enable_desc'),
        ]);

        $this->validateEvent($request);

        return DB::transaction(function () use ($request, $event) {
            // check if event type & location exist, if not, create them
            $type = $this->checkIfEventTypeExists($request->event_name_de, $request->event_name_en);
            $location = $this->checkIfLocationExists($request->event_location);
            // get type & location id
            $type_id = $type->id;
            $location_id = $location->id;

            $wasChanged = false;

            // handle event type & location----------------------------
            if ($event->event_type_id != $type_id) {
                $event->event_type_id = $type_id;
                $wasChanged = true;
            }
            if ($event->event_location_id != $location_id) {
                $event->event_location_id = $location_id;
                $wasChanged = true;
            }

            // handle event time--------------------------------------
            $event_start_new = Carbon::createFromFormat('Y-m-d\TH:i', $request->event_date_start, 'Europe/Berlin')
                ->setTimezone('UTC');

            $event_end_new = Carbon::createFromFormat('Y-m-d\TH:i', $request->event_date_end, 'Europe/Berlin')
                ->setTimezone('UTC');

            if ($event->date_start != $event_start_new) {
                $event->date_start = $event_start_new;
                $wasChanged = true;
            }
            if ($event->date_end != $event_end_new) {
                $event->date_end = $event_end_new;
                $wasChanged = true;
            }

            // handle description--------------------------------------
            list($desc_de_new, $desc_en_new) = $this->setDescription($request, $type);

            if ($event->desc_de_override != $desc_de_new) {
                $event->desc_de_override = $desc_de_new;
                $wasChanged = true;
            }
            if ($event->desc_en_override != $desc_en_new) {
                $event->desc_en_override = $desc_en_new;
                $wasChanged = true;
            }

            // apply changes--------------------------------------
            $id = $event->id;

            if ($wasChanged) {
                $event->sequence = $event->sequence + 1;
                $event->save();

                $this->generateICS();

                return redirect()
                    ->route('event.show', ['id' => $id])
                    ->with('success', __('events.update_success'));
            }
            return redirect()
                ->route('event.show', ['id' => $id])
                ->with('info', __('events.update_nothing'));
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $eventId)
    {
        $events = $this->get_events(0, $eventId); // get event (throws 404 if none found)
        $event = $events->first();

        $event->delete();

        $this->generateICS();

        return redirect()
            ->route('calendar')
            ->with('success', __('events.delete_success'));
    }

    private function validateEvent(Request $request): void
    {
        $request->validate([
            'event_date_start' =>   ['required', 'date', Rule::date()->format('Y-m-d\TH:i'),],
            'event_date_end' =>     ['required', 'date', 'after:event_date_start', Rule::date()->format('Y-m-d\TH:i'),],
            'event_location' =>     ['required', 'string', 'max:100'],
            'enable_desc' =>        ['required', 'boolean'],
            'event_name_de' =>      ['required', 'string', 'max:50'],
            'event_name_en' =>      ['required', 'string', 'max:50'],
            'event_desc_de' => ['nullable', 'string', 'max:1500', 'required_unless:event_desc_en,null,""'],
            'event_desc_en' => ['nullable', 'string', 'max:1500', 'required_unless:event_desc_de,null,""'],
        ]);
    }

    /**
     * Checks if event_type exists for given names. If not, it will be created. Returns Model.
     * @param $name_de
     * @param $name_en
     * @return Model
     */
    function checkIfEventTypeExists($name_de, $name_en): Model
    {
        $event_type = EventType::where('name_de', $name_de)->where('name_en', $name_en)->first();
        if (!$event_type) {
            $event_type = new EventType();
            $event_type->name_de = $name_de;
            $event_type->name_en = $name_en;
            $event_type->save();
        }
        return $event_type;
    }

    /**
     * Checks if event_location exists for given names. If not, it will be created. Returns Model.
     * @param $name
     * @return Model
     */
    function checkIfLocationExists($name): Model
    {
        $event_loc = EventLocation::where('name', $name)->first();
        if (!$event_loc) {
            $event_loc = new EventLocation();
            $event_loc->name = $name;
            $event_loc->save();
        }
        return $event_loc;
    }

    /**
     * @param Request $request
     * @param Model $type
     * @return array
     */
    function setDescription(Request $request, Model $type): array
    {
        $desc_de = "-";
        $desc_en = "-";
        if ($request->enable_desc) {
            if ($request->event_desc_de != null) {
                $desc_de = $request->event_desc_de;
            } elseif ($type->desc_de) {
                $desc_de = "";
            }

            if ($request->event_desc_en != null) {
                $desc_en = $request->event_desc_en;
            } elseif ($type->desc_en) {
                $desc_en = "";
            }
        }
        return array($desc_de, $desc_en);
    }
}
