<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Log;
use DB;

use App\Models\Message;
use App\Jobs\ProcessMessages;

class SendMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and Sends Messages to the SMS API, based on scheduling';

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
    public function handle()
    {
        Log::notice("Scheduler Runs");
        $messages = \App\Models\Message::where(function ($query) {
                        $now = \Carbon\Carbon::now()->addHours(1)->format('Y-m-d H:i');
                        $query->where('brodcast_time', '=', (string) $now)
                        ->orWhere('brodcast_time', '=', "None");
                    })
                    ->where('permission', "ALLOW")
                    ->where('sent', 0)
                    ->where('isDeleted', 0)
                    ->orderBy('updated_at', 'asc')
                    ->get();

        if (count($messages) > 0) {
            $count = count($messages);
            Log::notice("Begin processing of $count Messages");
            $process = (new ProcessMessages($messages));
            dispatch($process);
        } else {
            Log::info("NO MESSAGES TO BE SENT.");
        }
    }
}
