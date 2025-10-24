<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VlEducation extends Model
{
    protected $table = 'vl_education';
    protected $fillable = [
        'education_desc'
    ];

    public $timestamps = false;
}
