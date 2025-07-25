<?php

namespace Kaely\PmsHotel\Models;

use Kaely\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantAvailability extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pms_restaurant_availabilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'restaurant_id',
        'date',
        'time_slot',
        'available_capacity',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'time_slot' => 'datetime:H:i',
        'available_capacity' => 'integer',
        'is_available' => 'boolean',
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
            'restaurant_id' => ['required', 'exists:pms_restaurants,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', 'date_format:H:i'],
            'available_capacity' => ['required', 'integer', 'min:0'],
            'is_available' => ['boolean'],
        ];
    }

    /**
     * Get the permission prefix for the model.
     *
     * @return string
     */
    public static function getPermissionPrefix(): string
    {
        return 'restaurant_availabilities';
    }

    /**
     * Scope a query to search restaurant availabilities.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('restaurant', function ($restaurantQuery) use ($search) {
                $restaurantQuery->where('short_name', 'like', "%{$search}%")
                               ->orWhere('full_name', 'like', "%{$search}%");
            })
            ->orWhere('date', 'like', "%{$search}%")
            ->orWhere('time_slot', 'like', "%{$search}%");
        });
    }

    /**
     * Get the restaurant that owns the availability.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the restaurant reservations for the availability.
     */
    public function restaurantReservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class, 'availability_id');
    }
}