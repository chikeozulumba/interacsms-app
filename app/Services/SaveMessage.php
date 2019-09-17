<?php
namespace App\Services;

use Auth;
use Request;
use App\Models\User;
use App\Models\Message;
use App\Jobs\SendMessages;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use DateTime;

class SaveMessage
{
    protected $data;
    protected $notify = [];

    public function __construct ($data)
    {
        $this->data = $data;
    }

    public function saveMessage ($schedule)
    {
        $data = json_decode($this->data, TRUE);
        // return $data;
        if (Auth::check()) {
            $user = Auth::user();
            // Message particulars
            $type = [
                "flash" => $data["flash"],
                "format" => $data["format"],
            ];
            $meta = $data['metadata'];
            $contacts = $data['results'];
            $contacts__ = [];
            foreach ($contacts as $phone) {
                array_push($contacts__, $phone['format']);
            }
            // PROCEED TO SAVE MESSAGE
            $perm = "ALLOW";
            $count = count($contacts__);
            if ($count > 0) {
                if (Auth::user()->credit <= $meta['cost']) $perm = "DENY";
                // return $type['flash'];
                $contacts = implode(', ', $contacts__);
                $message = Message::create([
                    'user_id' => $user->id,
                    'sender' => $meta['sender'],
                    'content' => $meta['message'],
                    'pages' => $meta['pages'],
                    'volume' => $count * $meta['pages'],
                    'recipients' => $contacts,
                    'cost' => $meta['cost'],
                    "flash" => $type['flash'],
                    "format" => $type['format'],
                    'brodcast_time' => ($schedule == "null" ? "None" : $schedule),
                    'permission' => $perm,
                    'sent' => 0,
                ]);
                if ($message) {
                    $this->notify[] = [
                        "status" => 200,
                        "error" => false,
                        "message" => "Message Queued for processing."
                    ];
                } else {
                    $this->notify[] = [
                        "status" => 400,
                        "error" => true,
                        "message" => "Message could not be processed."
                    ];
                }
            } else {
                $this->notify[] = [
                    "status" => 419,
                    "error" => true,
                    "message" => "Unprocessable Entity."
                ];
                return $this->notify;
            }
        } else {
            $this->notify[] = [
                "status" => 401,
                "error" => true,
                "message" => "Unauthorized."
            ];
            return $this->notify;
        }
        return $this->notify;
    }

}
