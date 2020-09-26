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
            $sub_problem_id = $submission['problem_id'];
            if ( isset($submission_map[$sub_problem_id]) )
            {
                $first_ac_submission = $submission_map[$sub_problem_id];
                if ($first_ac_submission['id'] > $submission['id'])
                    $submission_map[$sub_problem_id] = $submission;
            }
            else
            {
                $submission_map[$sub_problem_id] = $submission;
            }
        }

        $submission_map = collect( $submission_map )->map( function($submission)
        {
            $submission['epoch_date'] = strtotime( date('Y-m-d', $submission['epoch_second']));
            return $submission;            
        });
        
        $submission_key = array_keys($submission_map->toArray());
        $diff_of_problems = Problems::whereIn('problem_id', $submission_key)->select(array('problem_id', 'difficulty'))->get();
        $sum_each_date = $submission_map->groupBy('epoch_date')->mapWithKeys(function ($problems, $date) use ($diff_of_problems)
        {
            $solved_diff_of_problems = $diff_of_problems->whereIn('problem_id', array_column($problems->toArray(), 'problem_id'));
            $sum = $solved_diff_of_problems->sum( function ($problem)
            {
                return (isset($problem) && isset($problem->difficulty)) ? $problem->difficulty : 0;
            });
            return [$date => $sum];
        });
        $sum_each_date = $sum_each_date->sortKeys();

        return collect( $sum_each_date )->keyBy(function ($sum, $date)
        {
            return date('Y-m-d', $date);    
        });
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

        $res = json_encode($res);
        return response($res, 200)
                    ->header('Content-Type', 'application/json')
                    ->header('Content-Length',  strlen($res));
    }
}
