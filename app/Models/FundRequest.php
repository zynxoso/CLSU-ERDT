<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class FundRequest extends Model
{
    use HasFactory, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholar_profile_id',
        'request_type_id',
        'reference_number',
        'amount',
        'purpose',
        'details',
        'status',
        'status_history',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'status_history' => 'array',
    ];

    /**
     * Get the scholar profile that owns the fund request.
     */
    public function scholarProfile()
    {
        return $this->belongsTo(ScholarProfile::class);
    }

    /**
     * Get the request type that owns the fund request.
     */
    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }



    /**
     * Get the disbursements for the fund request.
     */
    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    /**
     * Get the admin who reviewed the fund request.
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the user through the scholar profile.
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            ScholarProfile::class,
            'id', // Foreign key on scholar_profiles table
            'id', // Foreign key on users table
            'scholar_profile_id', // Local key on fund_requests table
            'user_id' // Local key on scholar_profiles table
        );
    }

    /**
     * Get the documents for the fund request.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Add a status change to the history
     *
     * @param string $status
     * @param string|null $notes
     * @return void
     */
    public function addStatusHistory(string $status, ?string $notes = null): void
    {
        $history = $this->status_history ?? [];

        $history[] = [
            'status' => $status,
            'notes' => $notes,
            'timestamp' => now()->toDateTimeString(),
            'user_id' => auth()->id(),
            'user_name' => auth()->user() ? auth()->user()->name : 'System',
        ];

        $this->status_history = $history;
        $this->save();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($fundRequest) {
            // If status is changing, add to history
            if ($fundRequest->isDirty('status')) {
                $oldStatus = $fundRequest->getOriginal('status');
                $newStatus = $fundRequest->status;

                // Only add to history if there's an actual change
                if ($oldStatus !== $newStatus) {
                    $history = $fundRequest->status_history ?? [];

                    $history[] = [
                        'status' => $newStatus,
                        'previous_status' => $oldStatus,
                        'timestamp' => now()->toDateTimeString(),
                        'user_id' => auth()->id(),
                        'user_name' => auth()->user() ? auth()->user()->name : 'System',
                    ];

                    $fundRequest->status_history = $history;
                }
            }
        });
    }
}
