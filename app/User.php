<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AtCoderApiController;
use App\Http\Controllers\AtCoderDiffScoresApiController;
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

    public function get_rank()    
    {
        return self::where('diff_sum', '>', $this->diff_sum)->count()+1;
    }

    public static function calculate_rank($diff_sum)
    {
        return self::where('diff_sum', '>', $diff_sum)->count()+1;
    }

    public static function update_diff_sum_by_id($user_id)
    {
        $api_con = new AtCoderDiffScoresApiController();
        $diff_sum = $api_con->get_sum($user_id);

        echo "ID  : " . $user_id . "\n";
        echo "sum : " . $diff_sum . "\n\n";
 
        if ($diff_sum >= 1000)        
        {
            $user = self::firstOrNew(['user_id' => $user_id]);
            $user->diff_sum = $diff_sum;
            $user->save();
        }
    }
}
