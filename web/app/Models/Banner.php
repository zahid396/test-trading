<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'title', 'subtitle', 'image', 'button_text', 'action_type',
    'action_product_id', 'action_url', 'is_active', 'sort_order',
    'media_type', 'video_url',
])]
class Banner extends Model
{
    /** @use HasFactory<Banner> */
    use HasFactory;

    /**
     * Get the product linked to the banner action.
     */
    public function actionProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'action_product_id');
    }

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Extract the YouTube video ID from the stored URL, if any.
     */
    public function youtubeId(): ?string
    {
        if (empty($this->video_url)) {
            return null;
        }

        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/|live\/))([A-Za-z0-9_-]{6,})/', $this->video_url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Whether this banner renders an on-site YouTube player.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'youtube' && $this->youtubeId() !== null;
    }

    /**
     * The URL the whole banner should link to, or null when none is set.
     */
    public function actionUrl(): ?string
    {
        if ($this->action_type === 'none') {
            return null;
        }

        if (in_array($this->action_type, ['product', 'product_page'], true)) {
            return $this->actionProduct && $this->actionProduct->is_active
                ? route('products.show', $this->actionProduct->slug)
                : null;
        }

        if ($this->action_type === 'external_url' && !empty($this->action_url)) {
            return $this->action_url;
        }

        return null;
    }
}
