<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class FoodType extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'pms_food_types';

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
                'unique:pms_food_types,name' . ($id ? ",{$id}" : ''),
            ],
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return 'food_types';
    }

    /**
     * Scope a query to search for food types.
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

    /**
     * Get the restaurants that belong to this food type.
     */
    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class, 'food_type', 'id');
    }
}