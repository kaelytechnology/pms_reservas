<?php

namespace Kaely\PmsHotel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomRateRule extends BaseModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'pms_room_rate_rules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'class',
        'room_type_name',
        'min_nights',
        'max_guests',
        'included_dinners',
        'rule_text',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'min_nights' => 'integer',
        'max_guests' => 'integer',
        'included_dinners' => 'integer',
        'is_active' => 'boolean',
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
            'code' => 'required|string|max:50',
            'class' => 'required|string|max:100',
            'room_type_name' => 'required|string|max:100',
            'min_nights' => 'required|integer|min:1',
            'max_guests' => 'required|integer|min:1',
            'included_dinners' => 'nullable|integer|min:0',
            'rule_text' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        if ($scenario === 'update') {
            // Make some fields optional for updates
            $rules['code'] = 'sometimes|' . $rules['code'];
            $rules['class'] = 'sometimes|' . $rules['class'];
            $rules['room_type_name'] = 'sometimes|' . $rules['room_type_name'];
            $rules['min_nights'] = 'sometimes|' . $rules['min_nights'];
            $rules['max_guests'] = 'sometimes|' . $rules['max_guests'];
        }

        return $rules;
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return 'room_rate_rules';
    }
}