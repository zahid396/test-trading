<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['platform', 'label', 'url', 'icon', 'is_active', 'sort_order'])]
class SocialLink extends Model
{
    /** @use HasFactory<SocialLink> */
    use HasFactory;

    /**
     * Scope a query to only include active social links.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
