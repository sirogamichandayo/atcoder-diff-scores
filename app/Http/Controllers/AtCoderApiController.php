<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AtCoderApiController extends Controller
{
    const USER_SUBMISSION_URL = 'https://kenkoooo.com/atcoder/atcoder-api/results?user=';
    const DIFF_OF_THE_PROBLEMS_URL = 'https://kenkoooo.com/atcoder/resources/problem-models.json';
    //
    public function get_user_submission($id)
    {
        $client = new Client();
        $res = $client->request("GET", self::USER_SUBMISSION_URL . $id, [
            'headers' => ['Accept-Encoding' => 'gzip'],
        ]);
        return json_decode($res->getBody(), true);
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
