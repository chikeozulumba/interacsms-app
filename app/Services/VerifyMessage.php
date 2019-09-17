<?php
namespace App\Services;

use Auth;
use Request;
use \libphonenumber\PhoneNumberUtil as Util;
use \libphonenumber\PhoneNumberToCarrierMapper as Carrier;
use \libphonenumber\PhoneNumberFormat as Format;
use \libphonenumber\geocoding\PhoneNumberOfflineGeocoder as Geo;
use \libphonenumber\PhoneNumberToTimeZonesMapper as Zone;
use \libphonenumber\NumberParseException as NE;
use Instasent\SMSCounter\SMSCounter;

class VerifyMessage
{
    public $payload;
    public $user;
    public $cost;
    public $strlen;
    public $recipients;
    public $costing;

    public function __construct($data)
    {
        $this->cost = 3.5;
        $this->payload = $data;
        $this->strlen = 160;
        $this->costing = [];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user = Auth::user();
        $phones__ = explode(",", str_replace(" ", "", $this->payload['recipients']));
        $util = Util::getInstance();
        $carrier = Carrier::getInstance();
        $geocoder = Geo::getInstance();
        $mapper = Zone::getInstance();
        $output = [];
        $failed = [];
        $flash = $this->payload['flash'];
        $format = $this->payload['format'];
        $unique_numbers = array_unique($phones__);
        $approved_numbers = [];
        foreach ($unique_numbers as $key => $value) {
            array_push($approved_numbers, $value);
        }
        // return $approved_numbers;
        $duplicates = array_diff_key( $phones__, $unique_numbers );
        for ($i=0; $i < count($approved_numbers) ; $i++) {
            $phone = $approved_numbers[$i];
            try {
                $proto = $util->parse($phone, "NG");
                $phone_ = $util->format($proto, Format::E164);
                $network = strtoupper($carrier->getNameForNumber($proto, "ng"));
                $geo = $geocoder->getDescriptionForNumber($proto, "en_NG", "NG");
                $zone = $mapper->getTimeZonesForNumber($proto);
                if ($util->isValidNumber($proto))
                {
                    switch ($network) {
                        case 'MTN':
                            $cost = 3.0;
                            break;

                        case 'GLO':
                            $cost = 3.0;
                            break;

                        case 'AIRTEL':
                            $cost = 3.5;
                            break;

                        case '9MOBILE':
                            $cost = 3.0;
                            break;

                        default:
                            $cost = 3.5;
                            break;
                    }
                    $cost = 1;
                    $out = [
                        "format" => $phone_,
                        "network" => $network !== "" ? $network : "LOCAL",
                        "time-zone" => $zone,
                        "geolocation" => $geo,
                        "cost" => $cost * ceil(strlen($this->payload['message'])/$this->strlen)
                    ];
                    array_push($output, $out);
                    array_push($this->costing, $cost);
                } else {
                    $failed[] = [
                        "format" => $phone,
                        "type" => "Invalid",
                        "error" => "Invalid Phone Numbers"
                    ];
                }

            } catch (NE $ne) {
                $failed[] = [
                    "format" => $phone,
                    "type" => $ne->getErrorType(),
                    "error" => $ne->__toString()
                ];
            }
        }
        $this->recipients = count($output);
        $metadata = $this->checkMessage($this->payload['message']);
        $complete = [
            'statusCode' => 200,
            'metadata' => $metadata,
            'results' => $output,
            'failed' => $failed,
            'flash' =>$flash,
            'format' =>$format,
            "duplicates" => $duplicates
        ];
        if (isset($data['schedule'])) {
            $complete['schedule'] = $schedule;
        }
        return @json_encode($complete);
    }

    private function checkMessage ($message)
    {
        $smsCounter = new SMSCounter();
        $countedMessage = $smsCounter->count($message);
        $sender = $this->payload['sender'];
        $num = $countedMessage->length;
        $type = "";
        switch($countedMessage->encoding) {
            case 'GSM_7BIT':
                $type = "Normal GSM Text: No Unicode Encoded Text Message: Special Characters used, page length is 160 characters per page";
            break;
            case 'UTF16':
                $type = "Unicode Encoded Text Message: Special Characters used, hence page length is shortened to 70 characters per page";
            break;
            case 'GSM_7BIT_EX':
                $type = "Normal GSM Text: No Unicode Encoded Text Message: Special Characters used, page length is 160 characters per page";
            break;
            default:
                $type = "Normal GSM Text: No Unicode Encoded Text Message: Special Characters used, page length is 160 characters per page";
            break;
        }
        $pages = $countedMessage->messages;
        $cost = array_sum($this->costing) * $pages;
        $credit = $cost > Auth::user()->credit ? false : true;
        if (strlen($sender) > 11)
        {
            $warning = "Sender I.D. invalid.\nMust not be more than 11 characters.";
        }
        if(!$credit)
        {
            $warning = "You do not have enough credits to proceed, \nkindly top up your account or contact our customer service agent for assistance";
        }
        else
        {
            $warning = null;
        }
        return @compact('pages', 'sender', 'cost', 'credit', 'warning', 'message', 'flash', 'format', 'type', 'num');
    }
}
