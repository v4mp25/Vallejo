<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualMaterial extends Model
{
    protected $fillable = [
        'user_id',
        'curso_id',
        'aula_id',
        'title',
        'description',
        'resource_type',
        'file_path',
        'external_url',
        'due_date',
        'bimestre',
        'classification',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class);
    }
}
