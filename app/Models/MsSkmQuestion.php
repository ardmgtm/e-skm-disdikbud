<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsSkmQuestion extends Model
{
    protected $table = 'ms_skm_question';
    protected $fillable = [
        'id_skm_header', 'id_skm_question_type', 'id_skm_indicator', 'question', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];
}
