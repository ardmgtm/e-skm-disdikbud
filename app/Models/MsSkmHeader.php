<?php
namespace App\Models;

use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MsSkmHeader extends Model
{
    use HasUuids, UserStamps;
    protected $table = 'ms_skm_header';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'uuid', 'name', 'title', 'description', 'start_date', 'end_date', 'is_published',
        'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'
    ];
    protected $casts = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function services()
    {
        return $this->hasMany(MsService::class, 'id_skm_header', 'id');
    }
}
