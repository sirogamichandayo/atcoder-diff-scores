<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    const USER_SUBMISSION_URL = 'https://kenkoooo.com/atcoder/atcoder-api/results?user=';
    const DIFF_OF_THE_PROBLEMS = 'https://kekoooo.com/atcoder/resources/problem-modes.json';

    public function home()
    {
        $data = [
            'ids' => '',
        ];
        return view('pages.home', $data);
    }

    public function show(Request $request)
    {
        $ids = $request->ids;
        $client = new \GuzzleHttp\Client;
        $response = $client->request(
            'GET',
            'https://kenkoooo.com/atcoder/resources/problem-models.json'
        );
        $json = $response->getBody()->getContents();

        \Log::info(gettype($json));
        \Log::info($response->getStatusCode());

        // split ids and get submission    
        /* $ids = $request->ids;
        $id_array = explode(',', $ids); */

/*         $submission_array = [];
        foreach($id_array as &$id)
        {
            $id = trim($id);

            $user_submisson = file_get_contents($USER_SUBMISSION_URL . $id);
            $user_submission = mb_convert_encoding($user_submission, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        } */
        /* \Log::info($id_array); */
        $data = [
            'ids'=>$ids,
        ];
        return view('pages.home', $data);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function about()    
    {
        return view('pages.about');
    }
}
