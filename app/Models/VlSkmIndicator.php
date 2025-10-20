<?php
namespace App\Models;

use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VlSkmIndicator extends Model
{
    protected $table = 'vl_skm_indicator';
    protected $fillable = [
        'id','indicator_name'
    ];
}
