<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!app()->environment('local'))
        {
            $this->deleteDocumentProfileCloadSearch(0);
        }
        App\User::truncate();
        App\Profile::truncate();
        App\ThreadUsers::truncate();
        App\Thread::truncate();
        App\SocialAccount::truncate();
        App\Message::truncate();
        App\Follow::truncate();
        App\Post::truncate();
        App\Event::truncate();
        App\Like::truncate();
        App\Review::truncate();
        App\Rate::truncate();
        App\SearchLog::truncate();
        // factory(App\User::class, 10)->create()
        // ->each(function($u) {
        //         $u->Profile()->save(factory(App\Profile::class)->make());
        //     });
        //factory(App\Profile::class, 10)->create();
    }
    
    private function deleteDocumentProfileCloadSearch($id){
        $client = \AWS::createClient('CloudSearchDomain');
        $data = [];
        if($id == 0){
            for ($i=0; $i <50 ; $i++) { 
               $data[] = [
                'type'=>'delete',
                'id' => $i,];
            }
        }else{
        $data[] = [
                'type'=>'delete',
                'id' => $id,
       ];
        }
       $result = $client->uploadDocuments([
            'contentType' => 'application/json', // REQUIRED
            'documents' => json_encode($data), // REQUIRED
        ]);
    }
};
