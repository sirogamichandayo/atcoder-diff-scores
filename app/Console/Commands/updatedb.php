<?php

namespace App\Console\Commands;

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
    protected $description = 'Update database';

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
        User::update_diff_sum();
    }
}
