<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individuals extends Model
{
  use HasFactory;

  protected $fillable = [
    'fname',
    'lname',
    'city',
    'national_code',
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
