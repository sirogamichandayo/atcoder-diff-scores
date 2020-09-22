<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AtCoderApiController;
use App\Http\Controllers\AtCoderDiffScoresApiController;
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

        $sum_datas = []; // [['id' => id, 'sum' => sum, 'rank' => rank], ...]
        $diff_graph_data = []; 
        $rate_graph_data = [];
        $diff_api_con = new AtCoderDiffScoresApiController();
        foreach ($id_array as $id)
        {
            $tmp_diff_graph = self::get_diff_graph_data($id);
            $diff_graph_data[] = $tmp_diff_graph;
            $tmp_rate_graph = self::get_rate_graph_data($id);
            $rate_graph_data[] = $tmp_rate_graph;

            $sum_data = [];    
            $sum_data['id'] = $id;
            
            $sum = end($tmp_diff_graph['data'])['y'];
            $sum_data['sum'] = ($sum) ? $sum : 0;
            $sum_data['rank'] = User::calculate_rank($sum) . ' th';

            $rate = end($tmp_rate_graph['data'])['y'];
            $sum_data['fillRatio'] = $rate >= 3200 ? 1.0 : ($rate % 400) / 400;
            $sum_data['color'] = self::get_rate_color($rate);

            $sum_datas[] = $sum_data;
        }

        $graph_data = [];
        for ($i = 0; $i < sizeof($diff_graph_data); $i++)
            array_push($graph_data, $rate_graph_data[$i], $diff_graph_data[$i]);

        $request->session()->flash('_old_input', ['raw_ids' => $raw_ids]);            
        $data = [
            'sum_datas' => $sum_datas,
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

    public function api_info(Request $request)
    {
        return view('pages.api_info', ['request' => $request]);
    }


//////////////////////////////
// private
//////////////////////////////

    private function get_rate_color($rate)
    {
        $rateColors = [
            "Black",
            "Grey",
            "Brown",
            "Green",
            "Cyan",
            "Blue",
            "Yellow",
            "Orange",
            "Red",
        ];
        $ind = min(floor($rate / 400), count($rateColors) - 2);
        
        return $rateColors[$ind+1];
    }

    private function get_diff_graph_data($id)    
    {
        $graph_data = [];
        $diff_api_con = new AtCoderDiffScoresApiController();
        $diff_per_date = $diff_api_con->get_solved_diff_per_date($id);

        $graph_data = [];
        $graph_data['label'] = $id . '(diff)';
        $graph_data['fill'] = false;
        $graph_data['borderWidth'] = 1;
        $graph_data['pointRadius'] = 0;
        $graph_data['lineTension'] = 0;
        $graph_data['yAxisID'] = 'diff';

        $diff_sum = 0;        
        foreach ($diff_per_date as $date => $sum)        
        {
            $diff_sum += $sum;
            $graph_data['data'][] = ['x' => $date, 'y' => $diff_sum];
        }

        return $graph_data;        
    }    

    public function get_rate_graph_data($user_id)    
    {
        $atcoder_api_con = new AtCoderApiController();
        $contests_info = collect( $atcoder_api_con->get_contests_each_user($user_id) );
        $contests_info = $contests_info->filter(function ($info)
        {
            return $info['IsRated'] == true;
        });

        $rate_each_date = [];

        foreach ($contests_info as $info)        
        {
            $rate_each_date[$info['EndDate']] = $info['NewRating'];
        }

        // make graph_data;        
        $graph_data['label'] = $user_id . '(rate)';
        $graph_data['fill'] = false;
        $graph_data['borderWidth'] = 2;
        $graph_data['pointRadius'] = 3;
        $graph_data['lineTension'] = 0;
        $graph_data['yAxisID'] = 'rate';

        foreach ($rate_each_date as $date => $rate)
            $graph_data['data'][] = ['x' => $date, 'y' => $rate];

        return $graph_data;
    }
}
