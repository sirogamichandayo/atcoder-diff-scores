<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\User;

class UpdateDiffSum implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;    
    public $user_ids;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_ids)
    {
        $this->user_ids = $user_ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach ($this->user_ids as $id)
        {
            User::update_diff_sum_by_id($id);
        }
    }
}
