<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitApproval extends Model
{
    use HasFactory;

    protected $fillable = [
    'permit_id',
    'approver_id',
    'step',
    'status',
    'remarks',
    'acted_at',
];
    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
