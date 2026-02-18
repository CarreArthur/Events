<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function create(string $token): View
    {
        $registration = Registration::with(['event', 'review'])
            ->where('invite_token', $token)
            ->firstOrFail();

        $event = $registration->event;

        if (! $this->canLeaveReview($registration)) {
            return view('reviews.create', [
                'event' => $event,
                'registration' => $registration,
                'alreadyReviewed' => false,
                'error' => $this->getReviewErrorMessage($registration),
            ]);
        }

        if ($registration->review) {
            return view('reviews.create', [
                'event' => $event,
                'registration' => $registration,
                'alreadyReviewed' => true,
                'error' => null,
            ]);
        }

        return view('reviews.create', [
            'event' => $event,
            'registration' => $registration,
            'alreadyReviewed' => false,
            'error' => null,
        ]);
    }

    public function store(Request $request, string $token)
    {
        $registration = Registration::with(['event', 'review'])
            ->where('invite_token', $token)
            ->firstOrFail();

        $event = $registration->event;

        if (! $this->canLeaveReview($registration)) {
            return redirect()
                ->route('reviews.create', $token)
                ->withErrors(['review' => $this->getReviewErrorMessage($registration)]);
        }

        if ($registration->review) {
            return redirect()
                ->route('reviews.create', $token)
                ->withErrors(['review' => 'Un avis a deja ete depose pour cette participation.']);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'event_id' => $event->id,
            'registration_id' => $registration->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        if ($event->is_public) {
            return redirect()
                ->route('events.show', $event->slug)
                ->with('success', 'Merci pour votre avis !');
        }

        return view('reviews.confirmed', [
            'event' => $event,
        ]);
    }

    private function canLeaveReview(Registration $registration): bool
    {
        if ($registration->status !== 'REGISTERED' || ! $registration->is_attending) {
            return false;
        }

        $event = $registration->event;
        $endAt = $event->date_end ?? $event->date_start;

        if ($endAt && $endAt->isFuture()) {
            return false;
        }

        return true;
    }

    private function getReviewErrorMessage(Registration $registration): string
    {
        if ($registration->status !== 'REGISTERED' || ! $registration->is_attending) {
            return "L'avis est reserve aux participants confirmes.";
        }

        $event = $registration->event;
        $endAt = $event->date_end ?? $event->date_start;

        if ($endAt && $endAt->isFuture()) {
            return "Vous pourrez laisser un avis apres la fin de l'evenement.";
        }

        return "Impossible de laisser un avis pour le moment.";
    }
}
