<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = ['name', 'path', 'owner_id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, relation: 'users');
    }
}
