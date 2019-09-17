<?php
namespace App\Services;

use Auth;
use Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Message\Log as Logs;

use Log;
use DB;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use DateTime;

class SortDeliveryReports
{
    protected $data;
    protected $notify = [];

    public function __construct ()
    {

    }

    public function exec ($data)
    {
        // Log::info($data);
        foreach ($data['results'] as $report) {
            $eachId = $report['messageId'];
            $messageId = $report['bulkId'];
            $to = $report['to'];
            $log = Logs::where('each_id', '=', $eachId)
                        ->where('message_id', '=', $messageId)
                        ->where('phone_number', '=', '+'.$to)
                        ->where('is_deliveryReport', 0)
                        ->first();
                        // Log::info($log);
            if($log) {
                $log->is_deliveryReport = 1;
                $log->mccMnc = $report['mccMnc'];
                $log->title = $report['from'];
                $log->message = $report['text'];
                $log->sentAt = $report['sentAt'];
                $log->doneAt = $report['doneAt'];
                $log->smsCount = $report['smsCount'];
                $log->price_per_message = $report['price']['pricePerMessage'];
                $log->price_currency = $report['price']['currency'];
                $log->delivery_status_groupId = $report['status']['groupId'];
                $log->delivery_status_groupName = $report['status']['groupName'];
                $log->delivery_status_id = $report['status']['id'];
                $log->delivery_status_name = $report['status']['name'];
                $log->delivery_status_description = $report['status']['description'];
                $log->delivery_error_groupId = $report['error']['groupId'];
                $log->delivery_error_groupName = $report['error']['groupName'];
                $log->delivery_error_id = $report['error']['id'];
                $log->delivery_error_name = $report['error']['name'];
                $log->delivery_error_description = $report['error']['description'];
                $log->delivery_error_permanent = $report['error']['permanent'];
                $user = User::find($log->user_id);
                $message = Message::find($log->message_id);
                if (($report['status']['name'] === 'REJECTED_DND') || ($report['error']['name'] === 'EC_DEST_ADDRESS_BLACKLISTED') || ($report['error']['id'] === 2050)) {
                    Log::info("DND");
                    $user->credit = $user->credit + $report['smsCount'];
                    $message->cost = $message->cost - $report['smsCount'];
                    Log::info("DND->".$message->cost);
                }
                $log->save();
                $message->deliveryReport = 1;
                $message->save();
                $user->save();
                Log::info("FINISH");
                $p_message = "Your delivery report for '$log->title' is ready.\nThank You.";
                $p_time = Carbon::now()->addHours(1);
                $payload = [
                    "message" => $p_message,
                    "time_log" => $p_time,
                    "title" => "DELIVERY REPORT READY",
                    'type' => "DELIVERY REPORT",
                    'user_id' => $user->id,
                    'message_id' => $messageId
                ];
                $process = (new \App\Jobs\SendDeliveryMailReport($user))->onQueue('low');
                $addMessageNotification = (new \App\Jobs\AddMessageNotification($payload))->onQueue('message_notifications');
                dispatch($addMessageNotification);
                dispatch($process);
            }
        }
    }
}
