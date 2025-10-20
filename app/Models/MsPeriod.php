<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsPeriod extends Model
{
    protected $table = 'ms_periods';
    protected $fillable = [
        'name', 'start_period', 'end_period', 'is_active', 'is_deleted'
    ];
}
