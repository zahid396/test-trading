<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable([
    'title', 'slug', 'company_name', 'location', 'employment_type',
    'salary_range', 'application_deadline', 'subtitle', 'description',
    'requirements', 'image', 'is_active', 'sort_order',
])]
class JobPosting extends Model
{
    /** @use HasFactory<JobPosting> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'application_deadline' => 'date',
        ];
    }

    /**
     * Get the applications for the job.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }

    /**
     * Scope a query to only include active job postings.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Whether applications are still open (deadline not passed).
     */
    public function isOpen(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if (! $this->application_deadline) {
            return true;
        }

        return Carbon::parse($this->application_deadline)->endOfDay()->isFuture();
    }
}
