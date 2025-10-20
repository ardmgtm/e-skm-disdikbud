<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VlOccupation extends Model
{
    protected $table = 'vl_occupation';
    protected $fillable = [
        'occupation_desc'
    ];
}
