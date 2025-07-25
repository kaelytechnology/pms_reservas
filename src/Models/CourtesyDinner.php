<?php

namespace Kaely\PmsHotel\Models;

use Kaely\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtesyDinner extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pms_courtesy_dinners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'restaurant_id',
        'dinner_date',
        'dinner_time',
        'people',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dinner_date' => 'date',
        'dinner_time' => 'datetime:H:i',
        'people' => 'integer',
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
            'reservation_id' => ['required', 'exists:pms_reservations,id'],
            'restaurant_id' => ['required', 'exists:pms_restaurants,id'],
            'dinner_date' => ['required', 'date', 'after_or_equal:today'],
            'dinner_time' => ['required', 'date_format:H:i'],
            'people' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', 'string', 'in:scheduled,confirmed,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get the permission prefix for the model.
     *
     * @return string
     */
    public static function getPermissionPrefix(): string
    {
        return 'courtesy_dinners';
    }

    /**
     * Scope a query to search courtesy dinners.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('status', 'like', "%{$search}%")
              ->orWhere('notes', 'like', "%{$search}%")
              ->orWhereHas('reservation', function ($reservationQuery) use ($search) {
                  $reservationQuery->where('guest_name', 'like', "%{$search}%")
                                  ->orWhere('confirmation_number', 'like', "%{$search}%");
              })
              ->orWhereHas('restaurant', function ($restaurantQuery) use ($search) {
                  $restaurantQuery->where('short_name', 'like', "%{$search}%")
                                 ->orWhere('full_name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Get the reservation that owns the courtesy dinner.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the restaurant that owns the courtesy dinner.
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}