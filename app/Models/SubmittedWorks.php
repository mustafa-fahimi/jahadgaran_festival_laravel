<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JahadiGroups;
use App\Models\Individuals;
use App\Models\Groups;
use App\Models\Scores;

class SubmittedWorks extends Model
{
  use HasFactory, SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $guarded = [
    'id',
  ];

  protected $hidden = [
    'jahadi_groups_id',
    'individuals_id',
    'groups_id',
    'deleted_at',
  ];

  public function jahadiGroups()
  {
    return $this->belongsTo(JahadiGroups::class, 'jahadi_groups_id');
  }

  public function individuals()
  {
    return $this->belongsTo(Individuals::class, 'individuals_id');
  }

  public function groups()
  {
    return $this->belongsTo(Groups::class, 'groups_id');
  }

  public function scores()
  {
    return $this->hasMany(Scores::class);
  }
}
