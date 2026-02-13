<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{


public function index(): View
{
    $events = Event::where('is_public', true)
        ->orderBy('date_start') 
        ->paginate(9);

    return view('events.index', compact('events'));
}

public function show(Event $event): View
{
    abort_if(! $event->is_public, 404);

    return view('events.show', compact('event'));
}

}
