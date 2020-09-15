<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AtCoderApiController extends Controller
{
    const USER_SUBMISSION_URL = 'https://kenkoooo.com/atcoder/atcoder-api/results?user=';
    const DIFF_OF_THE_PROBLEMS_URL = 'https://kenkoooo.com/atcoder/resources/problem-models.json';
    const SUBMISSION_AT_THE_TIME_URL = 'https://kenkoooo.com/atcoder/atcoder-api/v3/from/';
    const AC_COUNT_URL = 'https://kenkoooo.com/atcoder/resources/ac.json';
    const DELAY = 1000.0;

    //
    public function get_user_submission($id)
    {
        logger("   getting user submission.");
        logger("   ID = " . $id);
        $client = new Client();
        $res = $client->request("GET", self::USER_SUBMISSION_URL . $id, [
            'headers' => ['Accept-Encoding' => 'gzip'],
            'delay' => AtCoderApiController::DELAY,
        ]);

        return json_decode($res->getBody(), true);
    }

    public function get_user_list()
    {
        logger("   getting user list...");
        $client = new Client();
        $json = $client->request("GET", self::AC_COUNT_URL, [
            'headers' => ['Accept-Encoding' => 'gzip'],
            'delay' => AtCoderApiController::DELAY,
        ]);
        $json = json_decode($json->getBody(), true);
        $res = [];
        foreach($json as $data)
            $res[] = $data['user_id'];
        
        return  $res;
    }
    
    public function get_test_data_for_update_diff_sum()
    {
        $submissions = [
        [
            "problem_id"=>"problem1",
            "user_id"=>"sirogamichandayo",
            "result"=>"AC",
        ],
        [
            "problem_id"=>"problem2",
            "user_id"=>"sirogamichandayo",
            "result"=>"AC",
        ],
        [
            "problem_id"=>"problem3",
            "user_id"=>"sirogamichandayo",
            "result"=>"WA",
        ],
        [
            "problem_id"=>"problem4",
            "user_id"=>"kurogamichandayo",
            "result"=>"AC",
        ]];
        
        $diff_of_problem = [
            "problem1" => ["difficulty"=> 100], 
            "problem2"=> ["difficulty"=> 200], 
            "problem3"=> ["difficulty"=> 300], 
            "problem4"=> ["difficulty"=> 400],
        ];

        return [$submissions, $diff_of_problem];
    }
    
    public function get_diff_of_problems()    
    {
        $client = new Client();
        $res = $client->request("GET", self::DIFF_OF_THE_PROBLEMS_URL, [
            'headers' => ['Accept-Encoding' => 'gzip'],
        ]);
        $res = json_decode($res->getBody(), true);

        return array_map(function($model)
        {
            $model['difficulty'] = array_key_exists('difficulty', $model) ?
                                    clip_difficulty($model['difficulty']) : 0;
            return $model;
        }, $res);
    }

    public function get_contests_each_user($id)
    {
        $client = new Client();
        $res = $client->request("GET", self::get_user_contest_url($id), [
            'headers' => ['Accept-Encoding' => 'gzip'],
        ]);
        $res = json_decode($res->getBody(), true);

        return array_map(function($model)
        {
            $model['EndDate'] = date('Y-m-d', strtotime($model['EndTime']));
            return $model;
        }, $res);
    }

    private function get_user_contest_url($id)
    {
        return 'https://atcoder.jp/users/' . $id . '/history/json';
    }
}
