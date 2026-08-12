<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $data=$request->validate(['name'=>['nullable','string','max:100'],'email'=>['nullable','email','max:180'],'message'=>['required','string','max:2000']]);
        $token=Str::random(64); $id=DB::table('chat_conversations')->insertGetId(['token'=>$token,'visitor_name'=>$data['name']??null,'visitor_email'=>$data['email']??null,'status'=>'open','last_message_at'=>now(),'created_at'=>now(),'updated_at'=>now()]);
        DB::table('chat_messages')->insert(['conversation_id'=>$id,'sender'=>'visitor','body'=>$data['message'],'created_at'=>now(),'updated_at'=>now()]);
        return response()->json(['token'=>$token,'messages'=>$this->conversationMessages($id)]);
    }

    public function messages(string $token): JsonResponse
    {
        $conversation=DB::table('chat_conversations')->where('token',$token)->first(); abort_unless($conversation,404);
        return response()->json(['status'=>$conversation->status,'messages'=>$this->conversationMessages($conversation->id)]);
    }

    public function visitorReply(Request $request,string $token): JsonResponse
    {
        $conversation=DB::table('chat_conversations')->where('token',$token)->first(); abort_unless($conversation && $conversation->status==='open',404);
        $data=$request->validate(['message'=>['required','string','max:2000']]);
        DB::table('chat_messages')->insert(['conversation_id'=>$conversation->id,'sender'=>'visitor','body'=>$data['message'],'created_at'=>now(),'updated_at'=>now()]);
        DB::table('chat_conversations')->where('id',$conversation->id)->update(['last_message_at'=>now(),'updated_at'=>now()]);
        return response()->json(['messages'=>$this->conversationMessages($conversation->id)]);
    }

    public function index(Request $request): View
    {
        $conversations=DB::table('chat_conversations')->leftJoin('users','users.id','=','chat_conversations.assigned_to')->select('chat_conversations.*','users.name as agent_name')
            ->when($request->filled('status'),fn($q)=>$q->where('chat_conversations.status',$request->status))->orderByDesc('last_message_at')->get();
        foreach($conversations as $conversation){$conversation->messages=collect($this->conversationMessages($conversation->id));$conversation->unread=DB::table('chat_messages')->where('conversation_id',$conversation->id)->where('sender','visitor')->whereNull('read_at')->count();}
        if($request->integer('conversation')) DB::table('chat_messages')->where('conversation_id',$request->integer('conversation'))->where('sender','visitor')->update(['read_at'=>now()]);
        return view('admin.chat.index',compact('conversations'));
    }

    public function reply(Request $request,int $conversation): RedirectResponse
    {
        $data=$request->validate(['message'=>['required','string','max:2000']]);
        DB::table('chat_messages')->insert(['conversation_id'=>$conversation,'sender'=>'admin','user_id'=>$request->user()->id,'body'=>$data['message'],'read_at'=>now(),'created_at'=>now(),'updated_at'=>now()]);
        DB::table('chat_conversations')->where('id',$conversation)->update(['assigned_to'=>$request->user()->id,'last_message_at'=>now(),'updated_at'=>now()]);
        return back()->with('success','Reply sent to the website chat.');
    }

    public function close(int $conversation): RedirectResponse
    {
        DB::table('chat_conversations')->where('id',$conversation)->update(['status'=>'closed','updated_at'=>now()]); return back()->with('success','Conversation closed.');
    }

    private function conversationMessages(int $conversationId): array
    {
        return DB::table('chat_messages')->leftJoin('users','users.id','=','chat_messages.user_id')->where('conversation_id',$conversationId)->orderBy('chat_messages.id')->get(['chat_messages.id','chat_messages.sender','chat_messages.body','chat_messages.created_at','users.name as agent'])->map(fn($m)=>['id'=>$m->id,'sender'=>$m->sender,'body'=>$m->body,'time'=>\Carbon\Carbon::parse($m->created_at)->format('H:i'),'agent'=>$m->agent])->all();
    }
}
