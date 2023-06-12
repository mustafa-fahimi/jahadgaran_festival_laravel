<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scores;

class Referees extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $guarded = [
    'id',
  ];

  protected $hidden = [
    'current_verify_code',
    'deleted_at',
  ];

  public function scores()
  {
    return $this->hasMany(Scores::class);
  }
}
