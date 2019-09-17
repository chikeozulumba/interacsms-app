<?php
namespace App\Services;

use Auth;
use Request;
use Log;
use \libphonenumber\PhoneNumberUtil as Util;
use \libphonenumber\PhoneNumberToCarrierMapper as Carrier;
use \libphonenumber\PhoneNumberFormat as Format;
use \libphonenumber\geocoding\PhoneNumberOfflineGeocoder as Geo;
use \libphonenumber\PhoneNumberToTimeZonesMapper as Zone;
use \libphonenumber\NumberParseException as NE;

use App\Models\User;
use App\Models\Message\Log as LM;

use Carbon\Carbon;
use App\Events\MessageProcessed;
use App\Jobs\SendDeliveryMail;

use Ramsey\Uuid\Uuid;
use infobip\api\client\SendMultipleTextualSmsAdvanced;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\Destination;
use infobip\api\model\sms\mt\send\Message;
use infobip\api\model\sms\mt\send\textual\SMSAdvancedTextualRequest;


class SendMessage
{
    protected $payload;
    public $failed;
    public $passed;
    public $error;
    protected $infobip;

    public function __construct ($payload)
    {
        $this->payload = $payload;
        $this->passed = [];
        $this->failed = [];
        $this->error = [];
        $this->infobip = [
            "username" => env("INFOBIP_USERNAME"),
            "password" => env("INFOBIP_PASSWORD")
        ];
    }

    protected function validate ($numbers)
    {
        $phones__ = explode(",", str_replace(" ", "", $this->payload['recipients']));
        $util = Util::getInstance();
        $carrier = Carrier::getInstance();
        $geocoder = Geo::getInstance();
        $mapper = Zone::getInstance();
        $output = [];
        $failed = [];
        for ($i=0; $i < count($phones__) ; $i++) {
            $phone = $phones__[$i];
            try {
                $proto = $util->parse($phone, "NG");
                $phone_ = $util->format($proto, Format::E164);
                $network = strtoupper($carrier->getNameForNumber($proto, "ng"));
                $geo = $geocoder->getDescriptionForNumber($proto, "en_NG", "NG");
                $zone = $mapper->getTimeZonesForNumber($proto);
                if ($util->isValidNumber($proto))
                {
                    array_push($this->passed, $phone_);
                } else {
                    array_push($this->failed, $phone);
                }
            } catch (NE $ne) {
                array_push($this->failed, $phone);
            }
        }
        return $this->passed;
    }

    protected function verifyNumbers ($numbers)
    {
        $phones__ = explode(",", str_replace(" ", "", $numbers));
        $util = Util::getInstance();
        $carrier = Carrier::getInstance();
        $geocoder = Geo::getInstance();
        $mapper = Zone::getInstance();
        $output = [];
        $failed = [];
        for ($i=0; $i < count($phones__) ; $i++) {
            $phone = $phones__[$i];
            try {
                $proto = $util->parse($phone, "NG");
                $phone_ = $util->format($proto, Format::E164);
                $network = strtoupper($carrier->getNameForNumber($proto, "ng"));
                $geo = $geocoder->getDescriptionForNumber($proto, "en_NG", "NG");
                $zone = $mapper->getTimeZonesForNumber($proto);
                if ($util->isValidNumber($proto))
                {
                    array_push($this->passed, $phone_);
                } else {
                    array_push($this->failed, $phone);
                }
            } catch (NE $ne) {
                array_push($this->failed, $phone);
            }
        }
        return $this->passed;
    }

    public function sendMessage ()
    {
        Log::info($this->payload);
        $util = Util::getInstance();
        $carrier = Carrier::getInstance();
        $geocoder = Geo::getInstance();
        $mapper = Zone::getInstance();
        $load_messages = $this->payload;
        Log::info("PROCESSING ON @SendMessage.php");
        foreach ($load_messages as $lm) {
            $user = User::find($lm->user_id);
            if($user->credit >= $lm->cost) {
                $client = new SendMultipleTextualSmsAdvanced(new BasicAuthConfiguration($this->infobip["username"], $this->infobip["password"]));
                $phones = $this->verifyNumbers($lm->recipients);
                $message_content = $lm->content;
                $title = $lm->sender;
                $flash = $lm->flash;
                $messageId = $lm->id;
                Log::info($messageId);
                # EXECUTE
                foreach ($phones as $phone) {
                    $destination = new Destination();
                    $destination->setTo($phone);
                    $destinations[] = $destination;
                }
                // Message that uses $destinations array
                $message = new Message();
                $message->setFrom($title);
                $message->setDestinations($destinations);
                $message->setText($message_content);
                $message->setFlash($flash !== "no" ? true : false);
                $requestBody = new SMSAdvancedTextualRequest();
                $requestBody->setMessages([$message]);
                $requestBody->setBulkId($messageId);
                // Executing response
                $response = $client->execute($requestBody);
                foreach ($response->getMessages() as $index => $sentMessageInfo) {
                    $proto = $util->parse("+".$sentMessageInfo->getTo(), "NG");
                    $phone_ = $util->format($proto, Format::E164);
                    $network = strtoupper($carrier->getNameForNumber($proto, "ng"));
                    $geo = $geocoder->getDescriptionForNumber($proto, "en_NG", "NG");
                    $zone = $mapper->getTimeZonesForNumber($proto);
                    $mlog = LM::create([
                        'user_id' => $user->id,
                        'message_id' => $messageId,
                        'bulk_id' => $messageId,
                        'each_id' => $sentMessageInfo->getMessageId(),
                        'volume' => $sentMessageInfo->getSmsCount(),
                        'phone_number' => $phone_,
                        'delivery_status' => $sentMessageInfo->getStatus()->getName(),
                        'network' => $network,
                        'geolocation' => $geo,
                        'time_zone' => $zone[0],
                    ]);
                }
                $lm->sent = 1;
                $lm->save();
                $user->credit = $user->credit - $lm->cost;
                $user->save();
                $un = explode(' ',$user->name);
                $dt = Carbon::now();

                // PAYLOAD FOR NOTIFICATIONS
                $p_message = "Your message has been sent to your recipients.\nKindly check your sent messages in the next 10 minutes for delivery report";
                $p_time = Carbon::now()->addHours(1);
                $payload = [
                    "message" => $p_message,
                    "time_log" => $p_time,
                    "title" => "MESSAGE '$title' SENT",
                    'type' => "MESSAGE SENT",
                    'user_id' => $user->id,
                    'message_id' => $messageId
                ];
                // event(new MessageProcessed($payload, $user->id));
                $sendDeliveryMail = (new \App\Jobs\SendDeliveryMail($user))->onQueue('low');
                $addMessageNotification = (new \App\Jobs\AddMessageNotification($payload))->onQueue('message_notifications');
                dispatch($sendDeliveryMail);
                dispatch($addMessageNotification);
            } else {
                $p_message = "Your message could not be sent.\nKindly check your if your units are sufficient";
                $p_time = Carbon::now()->addHours(1);
                $payload = [
                    "message" => $p_message,
                    "time_log" => $p_time,
                    "title" => "MESSAGE FAILED",
                    'type' => "MESSAGE FAILED",
                    'user_id' => $user->id,
                    'message_id' => $messageId
                ];
                $addMessageNotification = (new \App\Jobs\AddMessageNotification($payload))->onQueue('message_notifications');
                dispatch($addMessageNotification);
            }
        }
        Log::info("DONE PROCESSING MESSAGE ON @SendMessage.php");
    }
}
