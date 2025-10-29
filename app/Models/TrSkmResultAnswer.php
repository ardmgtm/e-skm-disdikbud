<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrSkmResultAnswer extends Model
{
    protected $table = 'tr_skm_result_answer';
    protected $fillable = [
        'id_skm_result_header',
        'id_skm_question',
        'desc_skm_question',
        'id_skm_answer',
        'desc_skm_answer',
        'id_skm_indicator',
        'desc_skm_indicator',
        'value',
        'feedback'
    ];

    public $timestamps = false;

    public function indicator()
    {
        return $this->belongsTo(\App\Models\VlSkmIndicator::class, 'id_skm_indicator');
    }
}
