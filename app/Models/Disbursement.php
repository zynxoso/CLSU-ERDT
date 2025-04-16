<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fund_request_id',
        'reference_number',
        'amount',
        'disbursement_date',
        'payment_method',
        'payment_details',
        'status',
        'notes',
        'processed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'disbursement_date' => 'date',
    ];

    /**
     * Get the fund request that owns the disbursement.
     */
    public function fundRequest()
    {
        return $this->belongsTo(FundRequest::class);
    }

    /**
     * Get the admin who processed the disbursement.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
