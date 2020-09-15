<?php

namespace App\Console\Commands;

use App\Jobs\UpdateDiffSum;
use App\Http\Controllers\AtCoderApiController;
use Illuminate\Console\Command;
use App\User;


class updatedb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updatedb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create jobs to update the database.';

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
        echo "Accessing api ...";
        $con = new AtCoderApiController();
        $user_list = $con->get_user_list();
        /* foreach ($user_list as $user_id)
        {
            echo "id : " .  $user_id . "\n";
            UpdateDiffSum::dispatch($user_id);
        } */
        User::update_diff_sum_by_id($user_list[0]);
    }
}
