<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
  use HasFactory;

  protected $fillable = [
    'group_name',
    'established_year',
    'group_license_number',
    'group_institution',
    'group_city',
    'group_supervisor_fname',
    'group_supervisor_lname',
    'group_supervisor_national_code',
    'phone_number',
    'current_verify_code',
    'verify_code_count',
    'last_ip',
  ];

  protected $hidden = [
    'current_verify_code',
    'verify_code_count',
    'last_ip',
  ];

  public function submittedWorks()
  {
    return $this->hasMany(SubmittedWork::class);
  }
}
