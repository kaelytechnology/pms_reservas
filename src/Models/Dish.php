<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Builder;

class Dish extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'pms_dishes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'user_add',
        'user_edit', 
        'user_deleted',
    ];

    /**
     * Get the validation rules for the model.
     */
    public static function rules(int $id = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:pms_dishes,name' . ($id ? ",{$id}" : ''),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return 'dishes';
    }

    /**
     * Scope a query to search for dishes.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}