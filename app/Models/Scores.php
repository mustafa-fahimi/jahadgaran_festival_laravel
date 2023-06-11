<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scores extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  public function referee()
  {
    return $this->belongsTo(Referee::class);
  }

  public function submittedWork()
  {
    return $this->belongsTo(SubmittedWork::class);
  }
}
