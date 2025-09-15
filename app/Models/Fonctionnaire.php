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
    // Use is_deleted filtering with global scope.

   /* protected static function booted()
{
    static::addGlobalScope('notDeleted', function ($query) {
        $query->where('is_deleted', 0);
    });
} */
public function audits()
{
    return $this->belongsToMany(Audit::class, 'audit_fonctionnaire', 'fonctionnaire_id', 'audit_id')->withTimestamps();
}
}
