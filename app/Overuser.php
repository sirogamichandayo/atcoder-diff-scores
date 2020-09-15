<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overuser extends Model
{
    protected $table = 'over_user';
    //
    protected $fillable = ['user_id'];
}
