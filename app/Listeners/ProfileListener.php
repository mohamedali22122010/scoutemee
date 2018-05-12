<?php

namespace App\Listeners;

use App\Events\Event;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Profile;
use AWS;

class ProfileListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    

    /**
     * Handle the event.
     *
     * @param  SomeEvent  $event
     * @return void
     */
    public function handle($event)
    {
        //
        if(!app()->environment('local'))
        {
            Profile::created(function($profile) {
                $this->uploadDocumentProfileCloadSearch($profile->toArray());
            });
            Profile::updated(function($profile){
                $this->uploadDocumentProfileCloadSearch($profile->toArray(),true);
            });
            Profile::deleting(function($profile){
                $client = AWS::createClient('CloudSearchDomain');
                $this->deleteDocumentProfileCloadSearch($client, $profile->id);
            });
        }
    }
    
    private function deleteDocumentProfileCloadSearch($client,$id){
        $data[] = [
                'type'=>'delete',
                'id' => $id,
       ];
       $result = $client->uploadDocuments([
            'contentType' => 'application/json', // REQUIRED
            'documents' => json_encode($data), // REQUIRED
        ]);
        return $result;
    }
    
    private function uploadDocumentProfileCloadSearch($profileData = array(),$update = false)
    {
       $client = AWS::createClient('CloudSearchDomain');
       //if($update)
            //$this->deleteDocumentProfileCloadSearch($client, $profileData['id']);
       $fields = [
            'user_id'=> $profileData['user_id'],
            'full_name'=> $profileData['full_name'],
            'services'=> json_encode($profileData['services']),
            'gender'=> $profileData['gender'],
            'location'=> $profileData['location'],
            'latlon'=> $profileData['latitude'].','.$profileData['longitude'],
            'profile_image'=> $profileData['profile_image'],
            'profile_video'=> $profileData['profile_video'],
            'updated_at'=> str_replace(" ","T", $profileData['updated_at'])."Z",// to meet cloud search requirement 
            'price_average'=> Profile::calculatePriceAverage($profileData['services']),
       ];
       
       if(!empty($profileData['about']))
            $fields['about'] = $profileData['about'];
       if(!empty($profileData['geners']))
            $fields['geners'] = json_encode($profileData['geners']);
       if(!empty($profileData['influnced_by']))
            $fields['influnced_by'] = json_encode($profileData['influnced_by']);
       if(!empty($profileData['role']))
            $fields['role'] = json_encode($profileData['role']);
       if(!empty($profileData['tagline']))
            $fields['tagline'] = $profileData['tagline'];
       if(!empty($profileData['profile_url']))
            $fields['profile_url'] = $profileData['profile_url'];
       if(!empty($profileData['rate_average']))
            $fields['rating'] = $profileData['rate_average'];
       if(!empty($profileData['trending_score']))
            $fields['trending_score'] = $profileData['trending_score'];
       
       $data[] = [
                'type'=>'add',
                'id' => $profileData['id'],
                "fields"=> $fields,
       ];
       $result = $client->uploadDocuments([
            'contentType' => 'application/json', // REQUIRED
            'documents' => json_encode($data), // REQUIRED
        ]);
        /*$client1 = AWS::createClient('cloudsearch');
        $result1 = $client1->indexDocuments([
            'DomainName' => config('aws.DomainName'),//'scoutmee-dev', // REQUIRED
        ]);*/
        return $result;
    }
}
