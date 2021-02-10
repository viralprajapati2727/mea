<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Auth;
use File,DB,Validator,Str;;
use App\Models\MessageGroup;
use App\Models\MessageGroupMember;
use App\Models\Messages;
use App\Helpers\Helper;
use Carbon\Carbon;

/**
 * Member chat message trait 
 */
trait MemberMessage
{
    public static function createMessageGroup($request){
       
        $user = Auth::user();
        $group_id = Str::random(12);
        $status = 1;
       
        $group = MessageGroup::create([
                    'status' => $status, 
                    'created_by' => $user->id,
                ]);
        $last_id = $group->id;

        $unique_id = (int)$last_id;
        
        MessageGroup::where(['id' => $last_id])->update(['message_group_unique_id	' => $unique_id]);

        HelpCenterMember::firstOrCreate([
            'message_group_unique_id' => (int)$last_id,
            'sender_id' => (int)$user->id
        ]);

        $message = $request->comment ??  "";  
      
        MessageGroupMember::insert([
            'message_group_unique_id' => (int) $last_id,
            'sender_id' => (int)$user->id,
            'text' => $message,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $last_id;
    }

    public static function sendMessage($request)
    {
        $status = 0;
        
        $checkGruopId = MessageGroupMember::where('message_group_unique_id',$request->group_id)->where('sender_id',$request->sender_id)->exists();
    
        if(!$checkGruopId){
            MessageGroupMember::firstOrCreate([
                'message_group_unique_id' => (int)$request->group_id,
                'sender_id' => (int)$request->sender_id,
            ]);
        }
        
        $message = $request->type_msg;

        HelpCenterMessage::insert([
            'message_group_unique_id' => (int) $request->group_id,
            'sender_id' => (int)$request->sender_id,
            'text' => $message,
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
    }

    public static function getDetails($group_id)
    {
        $user_id = Auth::user()->id;

        $details = MessageGroup::with('members')->where('message_group_unique_id',$group_id)->first();

        return $details;
    }

    public static function getAllMessage($unique_id)
    {
        $getGroupId = MessageGroup::where('message_group_unique_id',$unique_id)->first()->id;
        
        $messages = Messages::with('members')->where('message_group_unique_id',$getGroupId)->latest()->paginate(config('constant.rpp'));

        return $messages;
    }
}
