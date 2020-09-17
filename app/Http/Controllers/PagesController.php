<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AtCoderApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use App\User;

class PagesController extends Controller
{
    public function home(Request $request)
    {
        if ($request->has('raw_ids'))
            $raw_ids = $request->raw_ids;
        
        $id_array = preg_split("/[\s,]+/", $raw_ids);
        $id_array = array_unique($id_array);

        $id_array = array_filter($id_array, function($id) {
            return ($id != '');
        });
        
        // $sum_diff_by_date_by_id := [ [id => [date => diff_sum, ...]] , ...]
        // $diff_info := [ ["id" => id, "sum" => sum, "rank" => rank], ...]
        // $rate_by_date_by_id := [ ["id" => [date => rate, ...]], ...]
        list( $sum_diff_by_date_by_id, $user_diff_info, $rate_by_date_by_id)
            = self::get_diff_and_rate($id_array);

        $graph_diff_data = self::get_diff_graph_data($sum_diff_by_date_by_id);
        $graph_rate_data = self::get_rate_graph_data($rate_by_date_by_id);

        $graph_data = [];
        for ($i = 0; $i < sizeof($graph_diff_data); $i++)
            array_push($graph_data, $graph_rate_data[$i], $graph_diff_data[$i]);

        $request->session()->flash('_old_input', ['raw_ids' => $raw_ids]);            
        $data = [
            'sum_data' => $user_diff_info,
            'graph_data' => ['datasets' => $graph_data],
        ];

        $response = new Response(view('pages.home', $data));
        return $response;
    }

    public function ranking(Request $request)
    {
        $id = $request->id;
        if ($request->has('id') && $id != "")
        {
            $users = User::where('user_id', 'ilike', '%'.$id.'%')->paginate(20);
        }
        else
        {
            $users = User::orderBy('diff_sum', 'desc')->paginate(20);
        }
        $request->session()->flash('_old_input', ['id' => $id]);

        return view('pages.ranking', ['posts' => $users]);
    }

    public function contact()
    {
        return view('pages.contact');
    }
//////////////////////////////
// private
/////////////////////////////    
    
    private function get_diff_and_rate($id_array)
    {
        $diff_by_date_by_id = [];
        $rate_by_date_by_id = [];
        $diff_info = [];  

        $atcoder_api_con = new AtCoderApiController();
        $diff_of_problems = $atcoder_api_con->get_diff_of_problems();

        foreach($id_array as &$id)
        {
            list($diff_by_date_by_id[$id], $diff_info[], $rate_by_date_by_id[$id]) = 
            (function() use ($atcoder_api_con, $diff_of_problems, $id)
            {
                // get data from AtCoder Problems API.    
                $submissions = $atcoder_api_con->get_user_submission($id);
                
                $ac_submissions = array_filter($submissions, function($submission)
                {
                    return $submission['result'] == 'AC';
                });
                
                $submission_map = []; // [problem_id => first_ac_submission, ...]
                foreach($ac_submissions as $submission)
                {
                    $sub_id = $submission['id'];
                    if (array_key_exists($sub_id, $submission_map))
                    {
                        $early_ac_submission = $submission_map[$sub_id];
                        if ($early_ac_submission['id'] > $sub_id)
                            $submission_map[$submission['problem_id']] = $submission;
                    }
                    else
                    {
                        $submission_map[$submission['problem_id']] = $submission;
                    }
                }

                $sum = 0;                
                $sum_by_date = []; // [date => by_diff_sum, ...]
                foreach($submission_map as $problem_id => $submission)
                {
                    $date = date("Y-m-d", $submission['epoch_second']);
                    $count = array_key_exists($date, $sum_by_date) ? $sum_by_date[$date] : 0;
                    if (array_key_exists($problem_id, $diff_of_problems))
                    {
                        $count += $diff_of_problems[$problem_id]["difficulty"];
                        $sum += $diff_of_problems[$problem_id]["difficulty"];
                    }
                    $sum_by_date[$date] = $count;
                }

                array_multisort(array_map("strtotime", array_keys($sum_by_date)),
                                SORT_ASC, $sum_by_date);

                // get rank from DB
                $rank = User::calculate_rank($sum) . ' th';
                
                $contests_info = $atcoder_api_con->get_contests_each_user($id);
                $contests_info = array_filter($contests_info, function($contest)
                {
                    return $contest['IsRated'] == true;
                });
                $rate_by_date = [];

                foreach ($contests_info as $contest_info)
                    $rate_by_date[$contest_info['EndDate']] = $contest_info['NewRating'];
                                
                return [$sum_by_date, 
                        array("id" => $id, "sum" => number_format($sum), "rank" => $rank),
                        $rate_by_date];
            })();
        }

        return [$diff_by_date_by_id, $diff_info, $rate_by_date_by_id];
    }


    private function get_diff_graph_data($sum_by_date_by_id)    
    {
        $graph_data = [];

        foreach ($sum_by_date_by_id as $id => $sum_by_date)
        {
            $graph_data_inner = [];

            $graph_data_inner["label"] = $id . '(diff)';
            $graph_data_inner["fill"] = false;
            $graph_data_inner["borderWidth"] = 1;
            $graph_data_inner["pointRadius"] = 0;
            $graph_data_inner["lineTension"] = 0;
            $graph_data_inner["yAxisID"] = 'diff';

            $diff_sum = 0;
            foreach ($sum_by_date as $date => $sum)
            {
                $diff_sum += $sum;
                $graph_data_inner["data"][] = ['x' => $date, 'y' => $diff_sum];
            }

            $graph_data[] = $graph_data_inner;
        }

        return $graph_data;
    }    

    public function get_rate_graph_data($rate_by_date_by_id)
    {
        $graph_data = [];

        foreach($rate_by_date_by_id as $id => $rate_by_date)
        {
            $graph_data_inner = [];

            $graph_data_inner["label"] = $id . '(rate)';
            $graph_data_inner["fill"] = false;
            $graph_data_inner["borderWidth"] = 2;
            $graph_data_inner["pointRadius"] = 3;
            $graph_data_inner["lineTension"] = 0;
            $graph_data_inner["yAxisID"] = 'rate';

            foreach ($rate_by_date as $date => $rate)            
                $graph_data_inner["data"][] = ['x' => $date, 'y' => $rate];

            $graph_data[] = $graph_data_inner;                
        }

        return $graph_data;
    }

}
