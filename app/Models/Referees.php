<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referees extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  public function scores()
  {
    return $this->hasMany(Score::class);
  }
}
