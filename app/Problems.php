<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AtCoderApiController;

class Problems extends Model
{
    //
    public $timestamps = false;  
    protected $fillable = [
         'problem_id',
        'difficulty'
    ];

    public static function update_difficulty()    
    {
        echo "Update difficulty...\n";
        $con = new AtCoderApiController();
        $diff_by_problems = $con->get_diff_of_problems();

        foreach ($diff_by_problems as $id => $data)
        {
            $diff = $data['difficulty'];
            echo "   ID   : " . $id . "\n";
            echo "   DIFF : " . $diff . "\n";
            $problem = Self::firstOrNew(['problem_id' => $id]);
            $problem->difficulty = $diff;
            $problem->save();
        }
    }
}
