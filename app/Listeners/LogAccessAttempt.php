<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class LogAccessAttempt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? ($event->credentials['username'] ?? 'unknown');
        
        DB::table('access_logs')->insert([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'email' => $email,
            'status' => 'failed',
            'details' => 'Failed login attempt',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
