<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker\Factory::create();

        DB::table('users')->insert([
            'user_id' => 'sirogamichandayo',
            'diff_sum' => 100,
        ]);
        for ($i = 0; $i < 10; ++$i)
        {
            DB::table('users')->insert([
                'user_id' => $faker->userName(),
                'diff_sum' => 0,
            ]);
        }
    }
}

