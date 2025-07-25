<?php

namespace Kaely\PmsHotel\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait HasUserTracking
{
    use SoftDeletes;

    /**
     * Boot the trait.
     */
    protected static function bootHasUserTracking(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_add = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->user_edit = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->user_deleted = Auth::id();
                $model->save();
            }
        });
    }

    /**
     * Get the user who added this record.
     */
    public function userAdd()
    {
        return $this->belongsTo(config('auth-package.models.user'), 'user_add');
    }

    /**
     * Get the user who last edited this record.
     */
    public function userEdit()
    {
        return $this->belongsTo(config('auth-package.models.user'), 'user_edit');
    }

    /**
     * Get the user who deleted this record.
     */
    public function userDeleted()
    {
        return $this->belongsTo(config('auth-package.models.user'), 'user_deleted');
    }

    /**
     * Get the fillable attributes for user tracking.
     */
    public function getUserTrackingFillable(): array
    {
        return ['user_add', 'user_edit', 'user_deleted'];
    }

    /**
     * Get the dates for user tracking.
     */
    public function getUserTrackingDates(): array
    {
        return ['deleted_at'];
    }
}