<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

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

    public function generateICS():void
    {
        $events = $this->get_events(78); // 1 year = ~53 weeks; 78 weeks = ~1.5 years

        foreach (config('app.available_locales') as $lang) {
            $ics = $this->genICSheader($lang);
            $ics .= $this->genICSbody($events, $lang);
            $ics .= $this->genICSfooter();

            // save ICS file
            $filename = "lgbt-hs-ansbach-events-$lang.ics";
            $path = 'ics/';

            Storage::disk('public')->put(
                $path.$filename,
                $ics
            );
        }
    }

    private function genICSheader(string $lang, string $year = ""): string
    {
        if ($lang === "de") {
            $calname = "LGBTQIA+ & friends Terminplan";
            if ($year !== "") {
                $calname .= " $year";
            }
            $caldesc = 'Terminplan';
            if ($year !== "") {
                $caldesc .= " $year";
            }
            $caldesc .= ' der queeren Jugendgruppe "LGBTQIA+ & friends" der HS Ansbach';
        }
        else if ($lang === "en") {
            $calname = "LGBTQIA+ & friends Events";
            if ($year !== "") {
                $calname .= " $year";
            }

            $caldesc = 'Events';
            if ($year !== "") {
                $caldesc .= " $year";
            }
            $caldesc .= ' of the queer youth group "LGBTQIA+ & friends" of the Ansbach University';
        }

        //uppercase the language
        $lang = strtoupper($lang);

        $header = "BEGIN:VCALENDAR\r\n";
        $header .= "PRODID:-//LGBT-HS-Ansbach//LGBT-HS-Ansbach//$lang\r\n";
        $header .= "VERSION:2.0\r\n";
        $header .= "CALSCALE:GREGORIAN\r\n";
        $header .= "METHOD:PUBLISH\r\n";
        $header .= $this->formatLineLength("X-WR-CALNAME:$calname");
        $header .= "X-WR-TIMEZONE:Europe/Berlin\r\n";
        $header .= $this->formatLineLength("X-WR-CALDESC:$caldesc");

        return $header;
    }

    private function genICSfooter(): string
    {
        $footer = "END:VCALENDAR";
        return $footer;
    }

    private function genICSbody(Collection $events, string $lang): string
    {
        $ics_body = "";
        foreach ($events as $event) {
            $ics_event = "BEGIN:VEVENT\r\n";
            $ics_event .= "DTSTART:" . $this->formatDateTime($event->date_start) . "\r\n";
            $ics_event .= "DTEND:" . $this->formatDateTime($event->date_end) . "\r\n";
            //DTSTAMP is the current time when this file is generated
            $ics_event .= "DTSTAMP:" . $this->formatDateTime(date("Y-m-d H:i:s")) . "\r\n";
            $ics_event .= "UID:" . $event->uid . "\r\n";
            $ics_event .= "CREATED:" . $this->formatDateTime($event->created_at) . "\r\n";
            $ics_event .= "LAST-MODIFIED:" . $this->formatDateTime($event->updated_at) . "\r\n";

            // for description:
            // if description has CRLF linebreaks, replace them with literal "\n"
            $event_desc_ovr = $event->{'desc_'.$lang.'_override'};
            if ($event_desc_ovr != null && $event_desc_ovr != "" && $event_desc_ovr != "-") {
                $event_desc_ovr = str_replace(["\r\n", "\n"], "\\n", $event_desc_ovr);
            }

            $event_desc_def = $event->event_type->{'desc_'.$lang};
            if ($event_desc_def != null && $event_desc_def != "") {
                $event_desc_def = str_replace(["\r\n", "\n"], "\\n", $event['event_type_desc']);
            }

            /* if $event_desc_ovr is "-" -> use no description at all
             * if desc is NULL -> use default description ($event_desc_def here)
             * if $event_desc_ovr is set -> use that description
             */
            if ($event_desc_ovr == "-") {
                // do nothing, no description
            } else if ($event_desc_ovr == null || $event_desc_ovr == "") {
                $ics_event .= $this->formatLineLength("DESCRIPTION:" . $event_desc_def);
            } else {
                $ics_event .= $this->formatLineLength("DESCRIPTION:" . $event_desc_ovr);
            }

            $ics_event .= $this->formatLineLength("LOCATION:" . $event->event_location->name);
            $ics_event .= "SEQUENCE:" . $event->sequence . "\r\n";
            $ics_event .= "STATUS:CONFIRMED\r\n";
            $ics_event .= $this->formatLineLength("SUMMARY:" . $event->event_type->{'name_'.$lang});
            $ics_event .= "TRANSP:OPAQUE\r\n";
            $ics_event .= "END:VEVENT\r\n";

            $ics_body .= $ics_event;
        }

        return $ics_body;
    }

    private function formatLineLength(string $textblock): string
    {
        // split the textblock into lines with a maximum length of 75 characters, excluding linebreak CRLF
        // the newly created line should start with 2 spaces
        $textblock = wordwrap($textblock, 74, "\r\n  ", true);

        //add linebreak at the end
        $textblock .= "\r\n";

        return $textblock;
    }

    private function formatDateTime(string $datetime): string
    {
        // datetime format from the database: "YYYY-MM-DD HH:MM:SS" / "Y-m-d H:i:s" in PHP
        // ICS format: "YYYYMMDDTHHMMSSZ", "T" = date/time separator, "Z" = UTC time
        $datetime = str_replace(" ", "T", $datetime);
        $datetime = str_replace("-", "", $datetime);
        $datetime = str_replace(":", "", $datetime);
        $datetime .= "Z";

        return $datetime;
    }
}
