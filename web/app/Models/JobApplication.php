<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'job_posting_id', 'applicant_name', 'applicant_email',
    'phone', 'cover_letter', 'status',
])]
class JobApplication extends Model
{
    /** @use HasFactory<JobApplication> */
    use HasFactory;

    /**
     * Get the job posting the application belongs to.
     */
    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }
}
