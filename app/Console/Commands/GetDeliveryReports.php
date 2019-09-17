<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use DB;

use App\Models\Message\Log as Logs;
use App\Models\Message;
use App\Services\SortDeliveryReports;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class GetDeliveryReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:delivery-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Contacts Infobip API every 5 minute to get delivery reports.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getPendingDeliveryReports ()
    {
        $pendingDeliveryMessages = [];
        $pending = Message::select('id')->where('deliveryReport', 0)->get();
        $allPending = [];
        if (count($pending) > 0) {
            $ids = [];
            foreach($pending as $id) array_push($ids, $id->id);
            foreach ($ids as $i) array_push($allPending, $i);
        }
        $idString = implode(',',$allPending);Log::info(strlen($idString));
        if (strlen($idString) > 0) {
            if (count($allPending) > 0) {
                $client = new Client();
                $result = $client->get("https://api.infobip.com//sms/1/logs?bulkId=".$idString, [
                    'headers' => [
                        'Authorization' => env("INFOBIP_AUTH"),
                        'Accept'     => 'application/json',
                    ]
                ]);
                $response = json_decode($result->getBody(), true);
            }
        } else $response = [];

        return $response;
    }

    public function handle()
    {
        Log::info("GETTING DELIVERY REPORTS STARTS");
        $deliveryReports = $this->getPendingDeliveryReports();
        if (count($deliveryReports) > 0) {
            $sortDeliveryReport = new SortDeliveryReports();
            $sortDeliveryReport->exec($deliveryReports);
            Log::info("COMPLETED SORTING DELIVERY REPORTS");
        } else {
            Log::info("NO DELIVERY REPORTS");
        }
    }
}
