<?php

namespace App\Listeners;

use App\Events\AccessDetectionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AccessIpRecordListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccessDetectionEvent  $event
     * @return void
     */
    public function handle(AccessDetectionEvent $event)
    {
        // ログ出力
        Log::info($event->name);
    }
}
