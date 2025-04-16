<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'required_documents',
        'max_amount',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'required_documents' => 'array',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the fund requests for the request type.
     */
    public function fundRequests()
    {
        return $this->hasMany(FundRequest::class);
    }
}
