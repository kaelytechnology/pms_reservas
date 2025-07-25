<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Restaurant extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'pms_restaurants';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'short_name',
        'full_name',
        'food_type',
        'min_capacity',
        'max_capacity',
        'total_capacity',
        'opening_time',
        'closing_time',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'short_name' => 'string',
        'full_name' => 'string',
        'food_type' => 'integer',
        'min_capacity' => 'integer',
        'max_capacity' => 'integer',
        'total_capacity' => 'integer',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
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
            'short_name' => [
                'required',
                'string',
                'max:50',
                'unique:pms_restaurants,short_name' . ($id ? ",{$id}" : ''),
            ],
            'full_name' => [
                'required',
                'string',
                'max:255',
                'unique:pms_restaurants,full_name' . ($id ? ",{$id}" : ''),
            ],
            'food_type' => 'required|integer|exists:pms_food_types,id',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1|gte:min_capacity',
            'total_capacity' => 'required|integer|min:1|gte:max_capacity',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return 'restaurants';
    }

    /**
     * Scope a query to search for restaurants.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('short_name', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Get the food type that belongs to this restaurant.
     */
    public function foodType(): BelongsTo
    {
        return $this->belongsTo(FoodType::class, 'food_type');
    }

    /**
     * Get the restaurant availabilities for this restaurant.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(RestaurantAvailability::class);
    }

    /**
     * Get the restaurant reservations for this restaurant.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    /**
     * Get the courtesy dinners for this restaurant.
     */
    public function courtesyDinners(): HasMany
    {
        return $this->hasMany(CourtesyDinner::class);
    }
}