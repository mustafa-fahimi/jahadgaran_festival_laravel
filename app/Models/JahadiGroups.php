<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JahadiGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
        'group_registeration_number',
        'group_register_date',
        'group_nature',
        'subset_nature',
        'group_state',
        'group_city',
        'group_activity_state',
        'group_activity_city',
        'group_established_year',
        'group_landline_number',
        'group_supervisor_fullname',
        'group_supervisor_phone',
        'group_supervisor_national_code',
        'group_supervisor_birth_date',
        'group_supervisor_birth_certificate_number',
        'group_supervisor_father_name',
        'group_start_activity_year',
        'is_agriculture',
        'is_cultural',
        'is_educational',
        'is_healthcare',
        'is_economic',
        'is_construction',
        'phone_number',
        'current_verify_code',
        'verify_code_count',
        'last_ip',
    ];
}
