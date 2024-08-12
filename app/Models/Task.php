<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $hidden = ['deleted_at', 'updated_at'];

    protected $fillable = ['title', 'description', 'is_completed', 'user_id', 'completed_at'];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    //relationship with user
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //reformat created_at
    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->format('d D Y h:ia');
    }

    //reformat completed_at
    public function getCompletedAtAttribute($value): ?string
    {
        //if completed_at is null return null
        if ($value === null) {
            return null;
        }
        return Carbon::parse($value)->format('d D Y h:ia');
    }
}
