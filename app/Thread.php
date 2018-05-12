<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //
    protected $fillable = ['id', 'title', 'place', 'subservice', 'accepted', 'date', 'duration' ,'proficiency'];
    
    
    /**
     * The thread that belong to the user.
     */
    public function users()
    {
        //return $this->hasManyThrough('App\User', 'App\ThreadUsers','user_id');
        return $this->belongsToMany('App\User','thread_users','thread_id', 'user_id');
    }
    
    public function messages()
    {
       return $this->hasMany('App\Message');
    }
    
    public function threadusers()
    {
       return $this->hasMany('App\ThreadUsers');
    }
    
    
}
