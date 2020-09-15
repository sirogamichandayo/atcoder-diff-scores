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
        echo "Accessing api ...\n";
        $over_user = Overuser::orderby('id', 'desc')->first();
        var_dump($over_user);
        var_dump($over_user['user_id']);
        
        $con = new AtCoderApiController();
        $user_list = $con->get_user_list();        
        $size = sizeof($user_list);

        $first = 0;        
        if ($over_user) 
        {
            for (; $first < $size; ++$first)
            {
                if ($over_user['user_id'] == $user_list[$first])
                {
                    ++$first;
                    break;    
                }
            }
        }
        var_dump($first);

        for ($i = 0; $i < 30000; ++$i)
        {
            $ind = ($first + $i) % $size;
            $user_id = $user_list[$ind];

            echo "id : " .  $user_id . "\n";
            UpdateDiffSum::dispatch($user_id, $i);
        }
    }
}
