<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AtCoderApiController;
use App\store_updateddb_index;

class User extends Model
{
    public $timestamps = false;    
    public $incrementing = false;
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
        protected $fillable = [
        'user_id',
        'diff_sum',
    ];

    public static function get_diff_sum_rank($diff_sum)
    {
        return self::where('diff_sum', '>', $diff_sum)->count()+1;
    }

    public static function update_diff_sum()
    {
        // diff_sum_by_id := [user_id => diff_sum, ...];
        echo 'Accessing the api...' . "\n";
        $atcoder_api_con = new AtCoderApiController();

        if (\App::environment('local'))                
        {
            list($submissions, $diff_of_problem) = 
                $atcoder_api_con->get_test_data_for_update_diff_sum();
            logger($diff_of_problem);
            logger($submissions);
        }
        else
        {
            $user_list = $atcoder_api_con->get_user_list();
            $diff_of_problem = $atcoder_api_con->get_diff_of_problems();
            echo 'user size : ' . sizeof($user_list) . "\n";
        }

        $size = sizeof($user_list);
        $index = store_updateddb_index::orderBy('id', 'desc')->first();
        $i = (is_null($index)) ? 0 : $index['index'];
        if ($i < 0 || $i >= $size) $i = 0;
                
        for (; $i < $size; ++$i)
        {
            echo "i : " . $i . "\n";    
            $user_id = $user_list[$i]['user_id'];
            echo "   Getting submission...";
            echo "   Id = " . $user_id;
            $submissions = $atcoder_api_con->get_user_submission($user_id);
            
            $ac_submissions = array_filter($submissions, function ($submission)
            {
                return $submission['result'] === 'AC';
            });

            // get unique solved problem id;                    
            $unique_solved_problems = [];
            foreach($ac_submissions as $submission)
                $unique_solved_problems[$submission['problem_id']] = null;

            $unique_solved_problems = array_keys($unique_solved_problems);
            echo "   sizeof problems" . sizeof($unique_solved_problems) . "\n";
            $diff_sum = 0;
            foreach ($unique_solved_problems as $problem_id)                    
                $diff_sum += $diff_of_problem[$problem_id]['difficulty'];

            echo "   diff_sum" . $diff_sum . "\n";

            self::create(['user_id' => $user_id, 'diff_sum' => $diff_sum]);
            store_updateddb_index::create(['index' => $i]);
        }
    }
}
