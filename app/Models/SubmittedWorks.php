<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedWorks extends Model
{
  use HasFactory;

  protected $fillable = [
    'jahadi_group_id',
    'individual_id',
    'group_id',
    'attachment_type',
    'description',
    'file_path',
  ];

  protected $hidden = [
    'jahadi_group_id',
    'individual_id',
    'group_id',
  ];

  public function jahadiGroups()
  {
    return $this->belongsTo(JahadiGroups::class);
  }

  public function individuals()
  {
    return $this->belongsTo(Individuals::class);
  }

  public function groups()
  {
    return $this->belongsTo(Groups::class);
  }
}
