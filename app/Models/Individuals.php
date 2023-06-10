<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individuals extends Model
{
  use HasFactory;

  protected $guarded = [
    'id',
  ];

  protected $hidden = [
    'current_verify_code',
    'verify_code_count',
    'last_ip',
  ];

  public function submittedWorks()
  {
    return $this->hasMany(SubmittedWorks::class);
  }
}
