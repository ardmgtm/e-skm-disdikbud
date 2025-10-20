<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsSkmHeader extends Model
{
    protected $table = 'ms_skm_header';
    protected $fillable = [
        'id_period', 'name', 'title', 'description', 'is_locked', 'is_deleted', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];
}
