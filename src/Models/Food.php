<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends BaseModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'pms_foods';

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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
     * Get the validation rules for this model.
     */
    public static function getValidationRules(string $scenario = 'create'): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];

        if ($scenario === 'update') {
            $rules['name'] = 'sometimes|' . $rules['name'];
        }

        return $rules;
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return 'foods';
    }

    /**
     * Scope a query to search by name or description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Get restaurant reservations that use this food.
     */
    public function restaurantReservations()
    {
        return $this->hasMany(RestaurantReservation::class, 'food_id');
    }
}