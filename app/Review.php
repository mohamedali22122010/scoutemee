<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['profile_id', 'user_id','review'];
    
    
    public function profile()
    {
       return $this->belongsTo('App\Profile','profile_id');
    }
    
    public function user()
    {
       return $this->belongsTo('App\User','user_id');
    }
}
