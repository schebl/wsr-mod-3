<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'path', 'owner_id'];

    protected $keyType = 'string';

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function accesses(): array
    {
        $result = [
            [
                'fullname' => $this->owner->fullname(),
                'email' => $this->owner->email,
                'type' => 'author',
            ],
        ];

        foreach ($this->users ?? [] as $user) {
            $result[] = [
                'fullname' => $user->fullname(),
                'email' => $user->email,
                'type' => 'co-author',
            ];
        }

        return $result;
    }
}
