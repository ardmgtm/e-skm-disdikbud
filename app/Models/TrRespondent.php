<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrRespondent extends Model
{
    protected $table = 'tr_respondent';
    protected $fillable = [
        'id_skm_result_header', 'gender', 'age', 'id_education', 'name_education', 'id_occupation', 'name_occupation'
    ];
}
