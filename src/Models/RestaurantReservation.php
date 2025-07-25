<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class RestaurantReservation extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected $table = 'pms_restaurant_reservations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'reservation_id',
        'restaurant_id',
        'event_id',
        'food_id',
        'dessert_id',
        'beverage_id',
        'decoration_id',
        'requirement_id',
        'availability_id',
        'people',
        'comment',
        'reservation_date',
        'reservation_time',
        'other',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'reservation_id' => 'integer',
        'restaurant_id' => 'integer',
        'event_id' => 'integer',
        'food_id' => 'integer',
        'dessert_id' => 'integer',
        'beverage_id' => 'integer',
        'decoration_id' => 'integer',
        'requirement_id' => 'integer',
        'availability_id' => 'integer',
        'people' => 'integer',
        'comment' => 'string',
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
        'other' => 'string',
        'status' => 'string',
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
            'reservation_id' => 'nullable|integer|exists:pms_reservations,id',
            'restaurant_id' => 'required|integer|exists:pms_restaurants,id',
            'event_id' => 'nullable|integer|exists:pms_events,id',
            'food_id' => 'nullable|integer|exists:pms_foods,id',
            'dessert_id' => 'nullable|integer|exists:pms_desserts,id',
            'beverage_id' => 'nullable|integer|exists:pms_beverages,id',
            'decoration_id' => 'nullable|integer|exists:pms_decorations,id',
            'requirement_id' => 'nullable|integer|exists:pms_special_requirements,id',
            'availability_id' => 'nullable|integer|exists:pms_restaurant_availability,id',
            'people' => 'required|integer|min:1',
            'comment' => 'nullable|string|max:1000',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'other' => 'nullable|string|max:500',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
        ];
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return 'restaurant_reservations';
    }

    /**
     * Scope a query to search for restaurant reservations.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('comment', 'like', "%{$search}%")
              ->orWhere('other', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhereHas('restaurant', function (Builder $subQ) use ($search) {
                  $subQ->where('short_name', 'like', "%{$search}%")
                       ->orWhere('full_name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Get the reservation that owns this restaurant reservation.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the restaurant that owns this reservation.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the event that owns this reservation.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the food that owns this reservation.
     */
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Get the dessert that owns this reservation.
     */
    public function dessert(): BelongsTo
    {
        return $this->belongsTo(Dessert::class);
    }

    /**
     * Get the beverage that owns this reservation.
     */
    public function beverage(): BelongsTo
    {
        return $this->belongsTo(Beverage::class);
    }

    /**
     * Get the decoration that owns this reservation.
     */
    public function decoration(): BelongsTo
    {
        return $this->belongsTo(Decoration::class);
    }

    /**
     * Get the special requirement that owns this reservation.
     */
    public function specialRequirement(): BelongsTo
    {
        return $this->belongsTo(SpecialRequirement::class, 'requirement_id');
    }

    /**
     * Get the restaurant availability that owns this reservation.
     */
    public function availability(): BelongsTo
    {
        return $this->belongsTo(RestaurantAvailability::class, 'availability_id');
    }
}