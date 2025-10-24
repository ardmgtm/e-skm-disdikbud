<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrSkmResultHeader extends Model
{
    protected $table = 'tr_skm_result_header';
    protected $fillable = [
        'id_skm_header', 'id_service', 'timestamps'
    ];

    public $timestamps = false;
}
