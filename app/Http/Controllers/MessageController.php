<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Message;
use App\ThreadUsers;
use App\Thread;
use App\User;
use App\Profile;

class MessageController extends HomeController
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        if(isset($request->title) && !empty($request->title))
        {
            $ThreadUsers = ThreadUsers::whereHas('thread',function($query) use ($request){
                $query->with('messages');
                $query->Where('threads.title','like',"%{$request->title}%");
            })->where('thread_users.user_id','=',Auth::user()->id)->orderBy('is_read','asc')->orderBy('updated_at','desc')->paginate(10);
        }else{
            $ThreadUsers = ThreadUsers::with(['thread'=>function($query){
                $query->with('messages');
            }])->where('thread_users.user_id','=',Auth::user()->id)->orderBy('is_read','asc')->orderBy('updated_at','desc')->paginate(10);
        }
        return view('message.index',compact('ThreadUsers'));
    }
    
    public function unread()
    {
        //
        $ThreadUsers = ThreadUsers::with(['thread'=>function($query){
            $query->with('messages');
        }])->where('is_read','=',0)->where('thread_users.user_id','=',Auth::user()->id)->orderBy('updated_at','desc')->paginate(10);
        return view('message.index',compact('ThreadUsers'));
    }
    // view messages in this thread
    public function view($id)
    {
        //
        if(!$this->isUserInThisThread($id)){
            abort(404, "you cant view this message");
        }
        $ThreadUsers = ThreadUsers::with(['thread'=>function($query){
            $query->with('messages');
        }])->where('thread_users.user_id','=',Auth::user()->id)->orderBy('updated_at','desc')->get();
        // update user to set read this message
        ThreadUsers::where('thread_id','=', $id)->where('is_read','=',0)->where('user_id','=', Auth::user()->id)->update(['is_read' => 1]);
        $messages = Message::where('thread_id',$id)->orderBy('created_at','asc')->get();
        return view('message.view',compact('messages','id','ThreadUsers'));
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSingleMessage($id)
    {
        //
        $message = new Message;
        if(!User::where('id','=',$id)->select('id')->count()){
           abort(404, "you cant send message to not found user");
        }
        if($id == Auth::user()->id){
            abort(404, "you cant send message to Yourself");
        }
        $reciverUserId = $id;
        return view('message.create',compact('message','reciverUserId'));
    }
    
    
    public function sendGroupMessage($threadId)
    {
        //
        $message = new Message;
        if(!$this->isUserInThisThread($threadId))
        {
            abort(404, "you cant send message to this group");
        }
        return view('message.reply',compact('message','threadId'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);
        $message = new Message;
        $oldThrad = false;
        if($request->thread_id){
            $threadUser = ThreadUsers::where("thread_id",'=',$request->thread_id)->where('user_id','=',Auth::user()->id)->get();
            if(empty($threadUser)){
                abort(404, 'Unauthorized action.');
            }
            $thread_id = $request->thread_id;
            $oldThrad = true;
        }else{
            if(!$request->users){
                abort(404, 'no found users');
            }
            // create new thread
            $thread = new Thread;
            $thread->title = $request->title;
            $thread->fill($request->except('_token'));
            $thread->save();
            $thread_id = $thread->id;
            // add send user to thread 
            $threadUser = new ThreadUsers;
            $threadUser->thread_id = $thread->id;
            $threadUser->user_id = Auth::user()->id;
            $threadUser->is_read = 1;
            $threadUser->save();
            if($request->users)
            {
                $request->users = array_unique($request->users);
                foreach ($request->users as $user_id) {
                    // add users to thread;
                    $threadUser = new ThreadUsers;
                    $threadUser->thread_id = $thread->id;
                    $threadUser->user_id = $user_id;
                    $profile = Profile::where('user_id','=',$user_id)->first();
                    $threadUser->is_read = 0;
                    $threadUser->save();
                }
                $userEmailsInThread = ThreadUsers::with('user')->where('thread_id','=', $thread_id)->where('user_id','=', Auth::user()->id)->get()->lists('UserEmail')->filter();
                if(!$userEmailsInThread->isEmpty()){
                    \Mail::later(86400,'mail.review_musican', ['profile'=>$profile,'serviceName'=>$request->title], function($message) use($userEmailsInThread){
                        $message->to($userEmailsInThread->toArray())->subject('REVIEW YOUR SCOUTMEE MUSICIAN');
                    });
                }
            }
            $extraData = $request->except(array('_token','users','thread_id','content'));
            if($extraData){
                $content = "";
                foreach ($extraData as $key => $value) {
                    if($key == 'title'){
                        $key = "Description";
                    }
                    if($key == 'subservice'){
                        $key = "Service";
                    }
                   $content .= ucfirst($key) ." : ".$value."<br />";
                }
                $message->thread_id = $thread_id;
                $message->creator_id = Auth::user()->id;
                $message->content = $content;
                $message->save();
                $message = new Message;
            }
        }
        $message->thread_id = $thread_id;
        $message->creator_id = Auth::user()->id;
        $message->content = $request->content;
        if($request->content == "Accepted the offer"){
            Thread::where("id",'=',$thread_id)->update(['accepted' => 1]);
            $userEmailsInThread = ThreadUsers::with('user')->where('thread_id','=', $thread_id)->where('user_id','!=', Auth::user()->id)->get()->lists('UserEmail')->filter();
            if(!$userEmailsInThread->isEmpty()){
                $thread = Thread::where("id",'=',$thread_id)->first();
                \Mail::send('mail.offer_accepted', array('profile' => Auth::user()->Profile,'thread_id'=>$thread_id,'serviceName'=>$thread->title), function($message) use ($userEmailsInThread){
                    $message->to($userEmailsInThread->toArray())->subject('OFFER ACCEPTED');
                });
            }
        }
        if($request->content == "Declined the offer"){
            Thread::where("id",'=',$thread_id)->update(['accepted' => 2]);
            $userEmailsInThread = ThreadUsers::with('user')->where('thread_id','=', $thread_id)->where('user_id','!=', Auth::user()->id)->get()->lists('UserEmail')->filter();
            if(!$userEmailsInThread->isEmpty()){
                $thread = Thread::where("id",'=',$thread_id)->first();
                \Mail::send('mail.offer_declined', array('profile' => Auth::user()->Profile,'thread_id'=>$thread_id,'serviceName'=>$thread->title), function($message) use($userEmailsInThread){
                    $message->to($userEmailsInThread->toArray())->subject('OFFER DECLINED');
                });
            }
        }
        $MusicanEmailsInThread = ThreadUsers::with('user')->where('thread_id','=', $thread_id)->where('user_id','!=', Auth::user()->id)->get()->lists('MusicanEmail')->filter();
        if(!$MusicanEmailsInThread->isEmpty()){
            \Mail::send('mail.musican_new_message', [], function($message) use($MusicanEmailsInThread){
                $message->to($MusicanEmailsInThread->toArray())->subject('YOU HAVE A NEW MESSAGE ON SCOUTMEE');
            });
        }
        $message->save();
        if($oldThrad){
            ThreadUsers::where('thread_id','=', $thread_id)->where('user_id','!=', Auth::user()->id)->update(['is_read' => 0]);
            return redirect()->route('message.view',$thread_id);
        }
        return redirect()->route('message.index');
    }
    
    protected function isUserInThisThread($thread_id)
    {
       return ThreadUsers::where("thread_id",'=',$thread_id)->where('user_id','=',Auth::user()->id)->count();
    }
    
    protected function canViewThisMessage($id)
    {
       $Threads = ThreadUsers::where("user_id",'=',Auth::user()->id)->lists('thread_id')->toArray();
        return $message = Message::with('thread')->where('id','=',$id)->whereIn('thread_id',$Threads)->first();
    }
    
}
