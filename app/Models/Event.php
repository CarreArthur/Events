<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Registration;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'date_start',
        'date_end',
        'location',
        'cover_image',
        'max_participants',
        'max_guests_per_registration',
        'is_public',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end'   => 'datetime',
        'is_public'  => 'boolean',
        'max_participants' => 'integer',
        'max_guests_per_registration' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    
}
