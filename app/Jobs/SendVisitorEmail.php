<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendVisitorEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected $mailable;
    protected $recipient;

    public function __construct($mailable, $recipient)
    {
        $this->mailable = $mailable;
        $this->recipient = $recipient;
    }

    public function handle()
    {
        try {
            Mail::to($this->recipient)->send($this->mailable);
        } catch (\Exception $e) {
            Log::error('Failed to send visitor email: ' . $e->getMessage(), [
                'recipient' => $this->recipient,
                'mailable' => get_class($this->mailable)
            ]);
            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('Visitor email job failed permanently: ' . $exception->getMessage(), [
            'recipient' => $this->recipient,
            'mailable' => get_class($this->mailable)
        ]);
    }
}