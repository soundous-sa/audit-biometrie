<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonctionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'matricule'
    ];
    // Simple accessor (in case something expects $f->full_name)
    public function getFullNameAttribute($value)
    {
        return $value ?? '';
    }
}
