<?php

namespace Kaely\PmsHotel\Models;

use Kaely\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dessert extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pms_desserts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_add',
        'user_edit',
        'user_deleted',
    ];

    /**
     * Get the validation rules for the model.
     *
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:pms_desserts,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get the permission prefix for the model.
     *
     * @return string
     */
    public static function getPermissionPrefix(): string
    {
        return 'desserts';
    }

    /**
     * Scope a query to search desserts.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Get the restaurant reservations for the dessert.
     */
    public function restaurantReservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class);
    }
}