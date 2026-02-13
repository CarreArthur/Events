<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Illuminate\Notifications\Notifiable;


class Registration extends Model
{
    use Notifiable;

    protected $fillable = [
        'event_id',
        'parent_registration_id',
        'invite_token',
        'guest_name',
        'guest_email',
        'status',
        'is_attending',
        'dietary_info',
        'email_sent_at',
        'registered_at',
    ];

    protected $casts = [
        'is_attending' => 'boolean',
        'email_sent_at' => 'datetime',
        'registered_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function parent()
    {
        return $this->belongsTo(Registration::class, 'parent_registration_id');
    }

    public function guests()
    {
        return $this->hasMany(Registration::class, 'parent_registration_id');
    }

    public function routeNotificationForMail($notification = null): string
{
    return $this->guest_email;
}
}
