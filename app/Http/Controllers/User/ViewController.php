<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use App\Models\User;
use App\Models\Notifications;
use App\Models\Message;
use App\Models\Contacts\Group;
use App\Models\Contacts\Contact;

class ViewController extends Controller
{
    public function updateViewMeta()
    {
        $user = User::find(Auth::user()->id);
        $units = $user->credit;
        $messages = Message::where('user_id', $user->id)->where('sent', '=', 1)->where('isDeleted', '=', 0)->where('permission', '=', "ALLOW")->get()->count();
        $contacts = Contact::where('user_id', $user->id)->where('isDeleted', '=', 0)->get()->count();
        $notifications = Notifications::where('user_id', $user->id)->where('isRead', 0)->where('isAllowed', 1)->orderBy('updated_at', 'asc')->get()->toArray();
        return json_encode(compact(
            'units', 'messages', 'contacts', 'notifications'
        ));
    }

    public function getDataForContacts(Request $request)
    {
        $query = '';
        // return $request->only('sort', 'direction');
        switch ($request->sort) {
            case 'FIRST NAME':
                $query = 'first_name';
                break;
            case 'LAST NAME':
                $query = 'last_name';
                break;
            case 'PHONE NUMBER 1':
                $query = 'phone_1';
                break;
            case 'PHONE NUMBER 2':
                $query = 'phone_2';
                break;
            case 'LOCATION':
                $query = 'location';
                break;
            case 'DATE ADDED':
                $query = 'created_at';
                break;
            default:
                $query = 'first_name';
                break;
        }
        if(Auth::check()) {
            $user = User::find(Auth::user()->id);
            $contacts = Contact::select('id', 'id as d_id','first_name', 'id as m_id', 'last_name', 'phone_1', 'phone_2', 'location', 'created_at')->where('user_id', $user->id)->where('isDeleted', 0)->orderBy($query, $request->direction)->get()->toArray();
            $groups = Group::select('id','user_id', 'id as d_id', 'name', 'description')->where('user_id', $user->id)->where('isDeleted', 0)->get()->toArray();
            return compact('contacts', 'groups');
        }

    }
}
