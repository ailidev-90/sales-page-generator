<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'description',
        'key_features',
        'target_audience',
        'price',
        'unique_selling_points',
        'tone',
        'template',
        'generated_content',
    ];

    protected function casts(): array
    {
        return [
            'generated_content' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function headline(): string
    {
        return (string) data_get($this->generated_content, 'headline', $this->product_name);
    }
}
