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
    public $user_id;
    public $ind;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $ind)
    {
        $this->user_id = $user_id;
        $this->ind = $ind;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        User::update_diff_sum_by_id($this->user_id, $ind);
    }
}
