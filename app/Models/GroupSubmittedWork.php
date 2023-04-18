<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSubmittedWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'attachment_type',
        'description',
        'file_path',
    ];

    public function groupData()
    {
        return $this->belongsTo(GroupData::class);
    }
}
