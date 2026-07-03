<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualTask extends Model
{
    protected $fillable = [
        'material_id',
        'user_id',
        'title',
        'description',
        'due_date',
        'submission_file_path',
        'submission_text',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(VirtualMaterial::class, 'material_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
