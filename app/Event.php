<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['profile_id', 'title', 'image', 'location', 'event_date', 'scoutmee_booked'];
    
    public function profile()
    {
       return $this->belongsTo('App\Profile','profile_id');
    }
    
}
