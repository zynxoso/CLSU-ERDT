<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Casts\EncryptedAttribute;
use Illuminate\Support\Facades\Log;

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
        'bank_account_number',
        'bank_name',
        'account_holder_name',
        'routing_number',
        'tax_identification_number',
        'amount_breakdown',
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
        // Encrypted financial fields
        'bank_account_number' => EncryptedAttribute::class,
        'bank_name' => EncryptedAttribute::class,
        'account_holder_name' => EncryptedAttribute::class,
        'routing_number' => EncryptedAttribute::class,
        'tax_identification_number' => EncryptedAttribute::class,
        'amount_breakdown' => EncryptedAttribute::class,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'bank_account_number',
        'routing_number',
        'tax_identification_number',
        'tax_identification_hash',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Create hash for tax identification number
            if (!empty($model->tax_identification_number)) {
                $model->tax_identification_hash = hash('sha256', $model->tax_identification_number);
            }

            // Log financial data creation
            Log::info('Fund request being created with financial data', [
                'user_id' => auth()->id(),
                'has_bank_account' => !empty($model->bank_account_number),
                'has_tax_id' => !empty($model->tax_identification_number),
                'amount' => $model->amount,
                'timestamp' => now(),
            ]);
        });

        static::created(function ($model) {
            // Log after creation when ID is available
            Log::info('Fund request created with financial data', [
                'user_id' => auth()->id(),
                'fund_request_id' => $model->getKey(),
                'has_bank_account' => !empty($model->bank_account_number),
                'has_tax_id' => !empty($model->tax_identification_number),
                'amount' => $model->amount,
                'timestamp' => now(),
            ]);
        });

        static::updating(function ($fundRequest) {
            // Update hash for tax identification number if changed
            if ($fundRequest->isDirty('tax_identification_number')) {
                $fundRequest->tax_identification_hash = !empty($fundRequest->tax_identification_number)
                    ? hash('sha256', $fundRequest->tax_identification_number)
                    : null;

                Log::info('Tax identification number updated', [
                    'user_id' => auth()->id(),
                    'fund_request_id' => $fundRequest->getKey(),
                    'timestamp' => now(),
                ]);
            }

            // Log updates to other financial fields
            $financialFields = ['bank_account_number', 'bank_name', 'routing_number', 'amount_breakdown'];
            foreach ($financialFields as $field) {
                if ($fundRequest->isDirty($field)) {
                    Log::info('Financial data updated', [
                        'user_id' => auth()->id(),
                        'fund_request_id' => $fundRequest->getKey(),
                        'field' => $field,
                        'timestamp' => now(),
                    ]);
                }
            }

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
     * Search scope for finding by tax identification number
     */
    public function scopeFindByTaxIdentificationNumber($query, $taxId)
    {
        Log::info('Tax identification number search performed', [
            'user_id' => auth()->id(),
            'timestamp' => now(),
        ]);

        return $query->where('tax_identification_hash', hash('sha256', $taxId));
    }

    /**
     * Get masked bank account number for display purposes
     */
    public function getMaskedBankAccountNumberAttribute()
    {
        $accountNumber = $this->getAttribute('bank_account_number');
        if (!$accountNumber) {
            return null;
        }

        $length = strlen($accountNumber);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        // Show only last 4 digits
        return str_repeat('*', $length - 4) . substr($accountNumber, -4);
    }

    /**
     * Get masked tax identification number for display purposes
     */
    public function getMaskedTaxIdentificationNumberAttribute()
    {
        $taxId = $this->getAttribute('tax_identification_number');
        if (!$taxId) {
            return null;
        }

        $length = strlen($taxId);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        // Show only last 4 characters
        return str_repeat('*', $length - 4) . substr($taxId, -4);
    }

    /**
     * Log sensitive financial data access
     */
    public function logFinancialDataAccess($field)
    {
        Log::info('Financial data accessed', [
            'user_id' => auth()->id(),
            'fund_request_id' => $this->getKey(),
            'field' => $field,
            'timestamp' => now(),
        ]);
    }
}
