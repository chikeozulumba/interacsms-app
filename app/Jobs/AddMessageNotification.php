<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Notifications;
use Log;

class AddMessageNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        Log::info($this->data['type']);
        Notifications::create([
            'user_id' => $this->data['user_id'],
            'message_id' => $this->data['message_id'],
            'type_message' => $this->data['type'],
            'title' => $this->data['title'],
            'time_log' => $this->data['time_log'],
            'message' => $this->data['message']
        ]);
    }
}
