<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Model;
use Kaely\PmsHotel\Traits\HasUserTracking;

abstract class BaseModel extends Model
{
    use HasUserTracking;

    /**
     * The database connection that should be used by the model.
     */
    protected $connection = 'tenant';

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'user_add',
        'user_edit',
        'user_deleted',
        'deleted_at',
    ];

    /**
     * Get the table name with prefix.
     */
    public function getTable(): string
    {
        if (!isset($this->table)) {
            $this->table = config('pms-hotel.database.table_prefix') . str_replace('\\', '', snake_case(str_replace('Model', '', class_basename($this))));
        }

        return parent::getTable();
    }

    /**
     * Scope a query to only include active records.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}