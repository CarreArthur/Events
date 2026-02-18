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

    $event->load(['reviews' => fn ($query) => $query->latest()]);
    $reviews = $event->reviews;
    $reviewsCount = $reviews->count();
    $averageRating = $reviewsCount > 0 ? round($reviews->avg('rating'), 1) : null;

    return view('events.show', compact('event', 'reviews', 'reviewsCount', 'averageRating'));
}

}
