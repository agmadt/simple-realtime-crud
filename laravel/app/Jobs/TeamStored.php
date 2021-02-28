<?php

namespace App\Jobs;

use Airtable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TeamStored implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $totalQueued = Redis::get('queued_job');
        $isAirtableFetching = Redis::get('airtable_fetch_process');

        if ($isAirtableFetching) {
            sleep(2);
        }
        
        Airtable::table('default')->create($this->payload);

        Redis::set('queued_job', $totalQueued -= 1);
        Redis::expire('all_team', 1); // to force expire all_team key
    }
}
