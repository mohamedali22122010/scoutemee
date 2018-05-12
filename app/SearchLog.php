<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'search_log';
    
    protected $fillable = ['type', 'search_key', 'geners', 'role', 'influenced_by', 'gender', 'location' ,'latitude' ,'longitude', 'live_performance', 'music_lessons'];
}
