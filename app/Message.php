<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = ['thread_id', 'creator_id','content'];
    
    public $titles = array(
        'test message'=>'test message',
        'test message 1'=>'test message 1',
        'test message 2'=>'test message 2',
        'test message 3'=>'test message 3',
        'test message 4'=>'test message 4',
        
    );
    
    /*public function users()
    {
        return $this->hasManyThrough('App\User', 'App\ThreadUsers', 'creator_id', 'thread_id');
    }*/
    
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }
    
    public function thread()
    {
       return $this->belongsTo('App\Thread');
    }
}
