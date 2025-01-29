<?php

namespace App\Jobs;

use App\Mail\LoginLogMail;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginLogJob implements ShouldQueue
{
    use Queueable;

    private $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(config('app.login_log_method') == 'mail') {
            Mail::to(config('app.login_log_email'))->send(new LoginLogMail($this->user));
            Log::info("Login Mail sent to ". config('app.login_log_email'));
        } else {
            Log::info("User Logged in at ". Carbon::now());
            Log::info($this->user);
            Mail::to(config('app.login_log_email'))->send(new LoginLogMail($this->user));
        }
    }
}
