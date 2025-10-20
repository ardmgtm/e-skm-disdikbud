<?php

namespace App\Traits;

trait UserStamps
{
    public static function bootUserStamps()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->id;
                $model->updated_by = auth()->user()->id;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->id;
            }
        });

        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->user()->id;
                if (method_exists($model, 'save')) {
                    $model->save();
                }
            }
        });
    }
}
