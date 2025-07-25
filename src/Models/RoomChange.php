<?php

namespace Kaely\PmsHotel\Models;

use Kaely\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomChange extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pms_room_changes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'old_room_number',
        'new_room_number',
        'change_date',
        'reason',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'change_date' => 'datetime',
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
            'old_room_number' => ['required', 'string', 'max:50'],
            'new_room_number' => ['required', 'string', 'max:50'],
            'change_date' => ['required', 'date'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', 'in:pending,approved,rejected,completed'],
        ];
    }

    /**
     * Get the permission prefix for the model.
     *
     * @return string
     */
    public static function getPermissionPrefix(): string
    {
        return 'room_changes';
    }

    /**
     * Scope a query to search room changes.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('old_room_number', 'like', "%{$search}%")
              ->orWhere('new_room_number', 'like', "%{$search}%")
              ->orWhere('reason', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhereHas('reservation', function ($reservationQuery) use ($search) {
                  $reservationQuery->where('guest_name', 'like', "%{$search}%")
                                  ->orWhere('confirmation_number', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Get the reservation that owns the room change.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}