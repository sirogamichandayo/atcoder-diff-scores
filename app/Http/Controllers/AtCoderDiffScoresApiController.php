<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Problems;
use App\Http\Controllers\AtCoderApiController;


class AtCoderDiffScoresApiController extends Controller
{
    //
    public function get_solved_diff_per_date($id)
    {
        $atcoder_api_con = new AtCoderApiController();
        $submissions = $atcoder_api_con->get_user_submission($id);

        $ac_submissions = array_filter($submissions, function($submissions)
        {
            return $submissions['result'] == 'AC';
        });
        
        $submission_map = []; // [problem_id => first_ac_submission, ...];
        foreach ($ac_submissions as $submission)
        {
            $sub_id = $submission['id'];
            if (array_key_exists($sub_id, $submission_map))
            {
                $first_ac_submission = $submission_map[$sub_id];
                if ($early_ac_submission['id'] > $sub_id)
                    $submission_map[$submission['problem_id']] = $submission;
            }
            else
            {
                $submission_map[$submission['problem_id']] = $submission;
            }
        }

        $sum_each_date = [];
        foreach ($submission_map as $problem_id => $submission)
        {
            $date = date('Y-m-d', $submission['epoch_second']);
            $sum_date = array_key_exists($date, $sum_each_date) ? $sum_each_date[$date] : 0;
            $problem = Problems::where('problem_id', $problem_id);
            if ($problem->exists())
                $sum_date += $problem->first()->difficulty;
            $sum_each_date[$date] = $sum_date;
        }

        ksort($sum_each_date);
        return $sum_each_date;                        
    }

    public function get_sum($id)    
    {
        $atcoder_api_con = new AtCoderApiController();
        $submissions = collect( $atcoder_api_con->get_user_submission($id) );

        $ac_submissions = $submissions->filter(function ($submission, $key)
        {
            return $submission['result'] == 'AC';    
        });

        $unique_solved_subs = $ac_submissions->unique('problem_id');
        $diff_sum = 0;
        foreach ($unique_solved_subs as $submission)
        {
            $problem_id = $submission['problem_id'];
            $problem = Problems::where('problem_id', $problem_id);
            if ($problem->exists())
            {
                $problem = $problem->first();
                $diff_sum += $problem['difficulty'];
            }
        }

        return $diff_sum;            
    }

    public function diff_per_date(Request $request)    
    {
        $user_id = $request->user_id;
        $data = self::get_solved_diff_per_date($user_id);
        $res = [];
        foreach ($data as $date => $diff)
            $res[] = ['date' => $date, 'difficulty_sum' => $diff];

        return response($res, 200)
                    ->header('Content-Type', 'application/json')
                    ->header('Content-Length',  strlen($res));
    }
}
