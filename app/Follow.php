<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = ['follower_id', 'followed_id'];
    
    public function follower()
    {
       return $this->belongsTo('App\User','follower_id');
    }
    
    public function followed()
    {
       return $this->belongsTo('App\Profile','followed_id');
    }
}
