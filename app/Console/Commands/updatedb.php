<?php

namespace App\Console\Commands;

use App\Jobs\UpdateDiffSum;
use App\Http\Controllers\AtCoderApiController;
use Illuminate\Console\Command;
use App\User;
use App\Overuser;


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
        $con = new AtCoderApiController();
        $user_list = $con->get_user_list();        
        $size = sizeof($user_list);

        for($left = 0; $left < $size; $left+=20)        
        {
            $length = 20;    
            $tmp = array_slice($user_list, $left, $length);

            foreach($tmp as $user_id)
            {
                echo "id : " . $user_id . "\n";
            }
            UpdateDiffSum::dispatch($tmp);
        }
    }
}
