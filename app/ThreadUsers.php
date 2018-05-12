<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadUsers extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'thread_users';
    
    public function user()
    {
       return $this->belongsTo('App\User','user_id');
    }
    
    public function thread()
    {
       return $this->belongsTo('App\Thread','thread_id');
    }
    
    public function getUserEmailAttribute(){
        if($this->user->confirmed && strpos($this->user->email, '@')  !== false)
            return $this->user->email;
    }
    
    public function getMusicanEmailAttribute(){
        if($this->user->type == 2 && $this->user->confirmed && strpos($this->user->email, '@')  !== false)
            return $this->user->email;
    }
}
