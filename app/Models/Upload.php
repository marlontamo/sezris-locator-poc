<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
        'permit_id',
    ];

    // Optional: If you want relationship with users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }
    public function getFileUrlAttribute()
{
    return asset('storage/' . $this->file_path);
}
}
