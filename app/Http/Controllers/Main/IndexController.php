<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Unicodeveloper\Paystack\Paystack;

use App\Services\VerifyMessage;
use App\Services\SendMessage;
use App\Services\SaveMessage;

use App\Models\Message;
use App\Models\Notifications;
use App\Models\Payment;
use App\Models\Contacts\Contact;
use App\Models\Contacts\Group;
use App\Models\Message\Draft;
use App\Models\Message\Log as LM;
use Log;
use DB;

use League\Csv\Reader;
use League\Csv\Statement;

class IndexController extends Controller
{
    public function verifyMessage (Request $request)
    {
        $data = $request->only('sender', 'recipients', 'message','flash', 'format');
        if ($request->hasFile('recipients')) {
            $file = $request->file('recipients');
            $fileName = $file->getClientOriginalName();
            $ext = explode('.', $fileName);
            $ext = end($ext);
            $exts = ['txt', 'csv'];
            $contacts = [];
            if(in_array($ext, $exts)) {
                switch ($ext) {
                    case 'txt':
                        $handle = fopen($file->getRealPath(), "r");
                        if ($handle) {
                            while (($line = fgets($handle)) !== false) {
                                $line = str_replace("\n", "", $line);
                                array_push($contacts, $line);
                            }
                            fclose($handle);
                        }
                        $data['recipients'] = implode(',', $contacts);
                        $verify = new VerifyMessage($data);
                        $response = $verify->handle();
                        break;
                    case 'csv':
                        $csv = Reader::createFromPath($file->getRealPath(), 'r');
                        $csv->setHeaderOffset(0);
                        $stmt = (new Statement())
                            ->offset(0);
                        $header = $csv->getHeader()[0];
                        $records = $stmt->process($csv);
                        foreach ($records as $record ) {
                            // var_dump($record);die;
                            array_push($contacts, $record[$header]);
                        }
                        array_push($contacts, $header);
                        $data['recipients'] = implode(',', $contacts);
                        $verify = new VerifyMessage($data);
                        $response = $verify->handle();
                        break;
                    default:
                        $response = 'null';
                        break;
                }
            }
        } else {
            $verify = new VerifyMessage($data);
            $response = $verify->handle();
        }
        return $response;
    }

    public function saveMessage (Request $request)
    {
        $data = $request->only('sender', 'recipients', 'message', 'schedule', 'format', 'flash', 'cost', 'format');
        $data = $this->verifyMessage($request);

        $save = new SaveMessage($data);
        $schedule = $request->schedule;
        $response = $save->saveMessage($schedule);

        return $response;
    }

    public function getMessage (Request $request)
    {
        $ui = Auth::user()->id;
        $messages = Message::select('id', 'sender', 'content', 'pages', 'volume', 'cost', 'brodcast_time', 'recipients', 'deliveryReport')
                                ->where('user_id', '=', $ui)
                                ->where('isDeleted', 0)
                                ->where('sent', 1)
                                ->orderBy('updated_at', 'DESC')
                                ->get()
                                ->toArray();
        return json_encode($messages);
    }

    public function getScheduledMessage (Request $request)
    {
        $ui = Auth::user()->id;
        $now = \Carbon\Carbon::now()->format('Y-m-d H:i');
        $messages = Message::select('id', 'sender', 'content', 'pages', 'volume', 'cost', 'brodcast_time', 'recipients', 'updated_at')
                                ->where('user_id', '=', $ui)
                                ->where('isDeleted', 0)
                                ->where('sent', 0)
                                ->where('brodcast_time', '!=', "None")
                                ->where('brodcast_time', '>', $now)
                                // ->where
                                ->orderBy('updated_at', 'DESC')
                                ->get()
                                ->toArray();
        return json_encode($messages);
    }

    public function getPayments (Request $request)
    {
        $user = Auth::user();
        $payment = Payment::select('trx_ref', 'amount', 'units', 'P_status as status', 'P_paid_at as paid_at', 'updated_at')
                            ->where('user_id', '=', $user->id)
                            ->orderBy('updated_at', 'desc')
                            ->get();
        return json_encode($payment);
    }

    public function getDraftMessage (Request $request)
    {
        $ui = Auth::user()->id;
        $messages = Draft::select('id', 'user_id', 'sender', 'message', 'contacts_list', 'standard', 'flash', 'updated_at')
                                ->where('user_id', '=', $ui)
                                ->where('isDeleted', 0)
                                ->orderBy('updated_at', 'DESC')
                                ->get()
                                ->toArray();
        return json_encode($messages);
    }

    public function deleteMessage (Request $request)
    {
        $ids = explode(',', $request->id);
        $message = Message::whereIn('id', $ids)->update(["isDeleted" => 1]);
        return $message;
    }

    public function deleteDraft (Request $request)
    {
        $ids = explode(',', $request->id);
        $draft = Draft::whereIn('id', $ids)->update(["isDeleted" => 1]);
        return $draft;
    }

    public function deleteContact (Request $request)
    {
        $ids = explode(',', $request->id);
        $contacts = Contact::whereIn('id', $ids)->update(["isDeleted" => 1]);
        return $contacts;
    }

    public function deleteGroup (Request $request)
    {
        $ids = explode(',', $request->id);
        $contacts = Group::whereIn('id', $ids)->update(["isDeleted" => 1]);
        return $contacts;
    }

    public function redirectToGateway(Request $request)
    {
        $paystack = new Paystack();
        Payment::create([
            'user_id' => Auth::user()->id,
            'email' => $request->email,
            'trx_ref' => $request->orderID,
            'reference' => $request->reference,
            'amount' => ($request->amount / 100),
            'units' => $request->quantity
        ]);
        return $paystack->getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */

    public function handleGatewayCallback(Request $request)
    {
        $user = Auth::user();
        $payment = Payment::select('*')
                            ->where("reference", $request->reference)
                            ->where("user_id", $user->id)
                            ->first();

        $paymentDetails = new Paystack();
        $pd = $paymentDetails->getPaymentData();
        $pstatus = $pd['status'];
        $pmessage = $pd['message'];
        $pdata = $pd['data'];
        $pId = $pdata['id'];
        $payout = ($pstatus == true && $pmessage == "Verification successful" && $pdata['status'] == "success" ? 1 : 0);

        if($payout === 1) {
            $user->credit = $user->credit + $payment->units;
            $user->save();
        }
        $payment->payout = $payout;
        $payment->P_status = $pdata['status'];
        $payment->P_message = $pdata['message'];
        $payment->P_reference = $pdata['reference'];
        $payment->P_paid_at = $pdata['paid_at'];
        $payment->P_created_at = $pdata['created_at'];
        $payment->P_ipAddress = $pdata['ip_address'];
        $payment->P_authorization_code = $pdata['authorization']['authorization_code'];
        $payment->P_bin = $pdata['authorization']['bin'];
        $payment->P_last4 = $pdata['authorization']['last4'];
        $payment->P_exp_month = $pdata['authorization']['exp_month'];
        $payment->P_exp_year = $pdata['authorization']['exp_year'];
        $payment->P_channel = $pdata['authorization']['channel'];
        $payment->P_card_type = $pdata['authorization']['card_type'];
        $payment->P_bank = $pdata['authorization']['bank'];
        $payment->P_country_code = $pdata['authorization']['country_code'];
        $payment->P_brand = $pdata['authorization']['brand'];
        $payment->P_signature = $pdata['authorization']['signature'];
        $payment->P_reusable = $pdata['authorization']['reusable'];
        $payment->P_customer_id = $pdata['customer']['id'];
        $payment->P_customer_code = $pdata['customer']['customer_code'];
        $payment->P_createdAt = $pdata['createdAt'];
        $payment->P_paidAt = $pdata['paidAt'];
        $payment->P_transaction_date = $pdata['transaction_date'];
        $payment->save();

        return redirect()->route('home');
    }

    public function getSingleContact (Request $request)
    {
        $contacts = Contact::select('id','first_name', 'last_name', 'phone_1 as phone', 'location')->where('id', $request->id)->where('isDeleted', 0)->first()->toArray();
        return json_encode($contacts);
    }

    public function editGroup (Request $request)
    {
        $group = Group::select('id','name', 'description', 'id as d_id', 'id as m_id')->where('id', $request->id)->first()->toArray();
        return json_encode($group);
    }

    public function updateSingleContact (Request $request)
    {
        $user = Auth::user();
        $contact = Contact::find($request->id);
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->phone_1 = $request->phone;
        $contact->location = $request->location;
        if ($contact->save())
        {
            $contacts = $user->contacts;
            $groups = $user->groups;
            $status = 1;
            return compact('status','contacts', 'groups');
        } else {
            return 0;
        }
        return 0;
    }

    public function updateGroup (Request $request)
    {
        $user = Auth::user();
        $group = Group::find($request->id);
        $group->name = $request->name;
        $group->description = $request->description;
        if ($group->save())
        {
            $contacts = $user->contacts;
            $groups = $user->groups;
            $status = 1;
            return compact('status','contacts', 'groups');
        } else {
            $contacts = $user->contacts;
            $groups = $user->groups;
            $status = 0;
            return compact('status','contacts', 'groups');
        }
        $contacts = $user->contacts;
        $groups = $user->groups;
        $status = 0;
        return compact('status','contacts', 'groups');
    }

    public function addNewContact (Request $request) {
        $user = Auth::user();
        $check = Contact::where('user_id', $user->id)->where('phone_1', $request->phone)->count();
        if ($check < 1) {
            $newContact = Contact::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'location' => $request->location,
                'group_id' => $request->group,
                'phone_1' => $request->phone,
                'user_id' => $user->id,
            ]);
            if ($newContact) {
                $contacts = $user->contacts;
                $groups = $user->groups;
                $status = 1;
                return compact('status','contacts', 'groups');
            } else {
                $contacts = $user->contacts;
                $groups = $user->groups;
                $status = 0;
                return compact('status','contacts', 'groups');
            }
        } else {
            $contacts = $user->contacts;
            $groups = $user->groups;
            $status = 2;
            return compact('status','contacts', 'groups');
        }
        $contacts = $user->contacts;
        $groups = $user->groups;
        $status = 0;
        return compact('status','contacts', 'groups');
    }

    public function addNewGroup (Request $request) {
        $user = Auth::user();
        $check = Group::where('name', $request->name)->count();
        if ($check < 1) {
            $newGroup = Group::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $user->id
            ]);
            if ($newGroup) {
                $contacts = $user->contacts;
                $groups = $user->groups;
                $status = 1;
                return compact('status','contacts', 'groups');
            } else {
                $contacts = $user->contacts;
                $groups = $user->groups;
                $status = 0;
                return compact('status','contacts', 'groups');
            }
        } else {
            $contacts = $user->contacts;
            $groups = $user->groups;
            $status = 2;
            return compact('status','contacts', 'groups');
        }
        $contacts = $user->contacts;
        $groups = $user->groups;
        $status = 0;
        return compact('status','contacts', 'groups');
    }

    public function draftMessage (Request $request) {
        $user = Auth::user();
        $draft = Draft::create([
            'user_id' => $user->id,
            'sender' => $request->sender,
            'message' => $request->message,
            'contacts_list' => $request->recipients,
            'standard' => $request->format,
            'flash' => $request->flash,
            'isDeleted' => 0
        ]);
        if (!$draft) {
            return 0;
        }
        return 1;
    }

    public function getSentMessage (Request $request) {
        $user = Auth::user();
        $message = Message::where('id', $request->message)
                            ->where('user_id', $user->id)
                            ->first();
        $message_logs = LM::where('message_id', $request->message)                          ->where('is_deliveryReport', 1)
                            ->where('user_id', $user->id)
                            ->get()->toArray();
        $carriers = [];
        foreach ($message_logs as $ml) {
            array_push($carriers, $ml['network']);
        }
        $networks = [];
        foreach ($carriers as $key => $val) {
            $networks[$key] = $val;
        }
        return json_encode(compact(
            'user',
            'message',
            'message_logs',
            'carriers'
        ));
    }

    public function closeNotifications (Request $request) {
        $userid = auth()->user()->id;
        $ids = explode(',', $request->ids);
        foreach ($ids as $id)
        {
            $noty = Notifications::where('id', '=', $id)
                                    ->where('user_id', '=', $userid)
                                    ->first();
            if($noty)
            {
                $noty->isRead = 1;
                $noty->save();
            }
        }
        return 1;
    }
}
