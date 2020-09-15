<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AtCoderApiController;
use App\Overuser;

class User extends Model
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;    
    public $incrementing = false;
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

        $user_list = $atcoder_api_con->get_user_list();
        echo 'user size : ' . sizeof($user_list) . "\n";

        foreach ($user_list as $user_id)        
            self::update_diff_sum_by_id($user_id);
    }

    public static function update_diff_sum_by_id($user_id)
    {
        $atcoder_api_con = new AtCoderApiController();
        $diff_of_problem = $atcoder_api_con->get_diff_of_problems();
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

        echo "ID  : " . $user_id . "\n";
        echo "AC  : " . sizeof($unique_solved_problems) . "\n";
        echo "sum : " . $diff_sum . "\n\n";
 
        if ($diff_sum >= 1000)        
        {
            $user = self::firstOrNew(['user_id' => $user_id]);
            $user->diff_sum = $diff_sum;
            $user->save();
        }
    }
}
