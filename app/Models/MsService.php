<?php
namespace App\Models;

use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsService extends Model
{
    use UserStamps, SoftDeletes;
    protected $table = 'ms_service';
    protected $fillable = [
        'service_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'
    ];
}
