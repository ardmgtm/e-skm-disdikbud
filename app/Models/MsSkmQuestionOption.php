<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsSkmQuestionOption extends Model
{
    protected $table = 'ms_skm_question_option';
    protected $fillable = [
        'id_skm_question', 'order_number', 'option_label', 'option_value', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];
}
