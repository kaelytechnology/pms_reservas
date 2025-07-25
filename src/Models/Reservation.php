<?php

namespace Kaely\PmsHotel\Models;

use Kaely\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pms_reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'confirmation_number',
        'guest_name',
        'guest_email',
        'guest_phone',
        'room_number',
        'room_type',
        'check_in_date',
        'check_out_date',
        'adults',
        'children',
        'total_amount',
        'status',
        'special_requests',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'total_amount' => 'decimal:2',
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
            'confirmation_number' => ['required', 'string', 'max:50', 'unique:pms_reservations,confirmation_number'],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:20'],
            'room_number' => ['required', 'string', 'max:50'],
            'room_type' => ['required', 'string', 'max:100'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'adults' => ['required', 'integer', 'min:1', 'max:10'],
            'children' => ['nullable', 'integer', 'min:0', 'max:10'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Get the permission prefix for the model.
     *
     * @return string
     */
    public static function getPermissionPrefix(): string
    {
        return 'reservations';
    }

    /**
     * Scope a query to search reservations.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('confirmation_number', 'like', "%{$search}%")
              ->orWhere('guest_name', 'like', "%{$search}%")
              ->orWhere('guest_email', 'like', "%{$search}%")
              ->orWhere('guest_phone', 'like', "%{$search}%")
              ->orWhere('room_number', 'like', "%{$search}%")
              ->orWhere('room_type', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%");
        });
    }

    /**
     * Get the restaurant reservations for the reservation.
     */
    public function restaurantReservations(): HasMany
    {
        return $this->hasMany(RestaurantReservation::class);
    }

    /**
     * Get the room changes for the reservation.
     */
    public function roomChanges(): HasMany
    {
        return $this->hasMany(RoomChange::class);
    }

    /**
     * Get the courtesy dinners for the reservation.
     */
    public function courtesyDinners(): HasMany
    {
        return $this->hasMany(CourtesyDinner::class);
    }
}