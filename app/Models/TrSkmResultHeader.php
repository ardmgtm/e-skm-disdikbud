<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TrSkmResultHeader extends Model
{
    protected $table = 'tr_skm_result_header';
    protected $fillable = [
        'id_skm_header', 'id_service', 'timestamps', 'notes'
    ];

    public $timestamps = false;

    public function service(): BelongsTo
    {
        return $this->belongsTo(\App\Models\MsService::class, 'id_service');
    }

    public function respondent(): HasOne
    {
        return $this->hasOne(\App\Models\TrRespondent::class, 'id_skm_result_header');
    }
}
