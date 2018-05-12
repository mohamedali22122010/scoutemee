<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use AWS;
use Storage;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'type', 'password', 'confirmed', 'confirmation_code'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The thread that belong to the user.
     */
    public function thread()
    {
        return $this->belongsToMany('App\User','thread_users', 'user_id','thread_id');
    }
    
    public function threadusers()
    {
       return $this->hasMany('App\ThreadUsers');
    }
    /**
     * Get the message for the user.
     */
    public function Message()
    {
        return $this->hasMany('App\Message', 'creator_id');
    }
    
    public function Profile()
    {
        return $this->hasOne('App\Profile', 'user_id');
    }
    
    public function getimageUrl($path,$visibility=null)
    {
       if(Storage::getDefaultDriver() == 's3') {
            //createPresignedRequest
            $s3 = AWS::createClient('S3');
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => env('AWS_S3_BUCKET'),
                'Key'    => $path,
            ]);
            if($visibility=='public'){
                return $s3->getObjectUrl(env('AWS_S3_BUCKET'),$path);
            }
            $request =  $s3->createPresignedRequest($cmd, '+30 minutes');
            return (string) $request->getUri();
        }
        if(Storage::getDefaultDriver() == 'local') {
            return \URL::to('/storage/app/'.$path);
        }
        return \URL::to('/storage/app/'.$path);
    }
    
}
