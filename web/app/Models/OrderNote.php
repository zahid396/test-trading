<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'note', 'created_by'])]
class OrderNote extends Model
{
    /** @use HasFactory<OrderNote> */
    use HasFactory;

    /**
     * Get the order that owns the note.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
