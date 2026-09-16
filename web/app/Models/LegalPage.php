<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['slug', 'title', 'content'])]
class LegalPage extends Model
{
    /**
     * Get a legal page by slug.
     */
    public static function getBySlug(string $slug): ?static
    {
        return static::where('slug', $slug)->first();
    }
}
