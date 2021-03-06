<?php

namespace Nikservik\Subscriptions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Nikservik\Subscriptions\Facades\Subscriptions;

class WarnBeforeEnd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $processed = Subscriptions::warnBeforeEnd();    
        activity()->withProperties(['job' => 'WarnBeforeEnd', 'processed' => $processed])->log('executed');
    }
}
