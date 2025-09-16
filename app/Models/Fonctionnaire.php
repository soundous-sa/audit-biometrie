<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonctionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'firstName',
        'lastName',
        'phone',
        'matricule'
    ];

    // Ensure that when setting full_name we also derive firstName/lastName if missing
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->synchronizeNameParts();
        });
        static::updating(function ($model) {
            $model->synchronizeNameParts();
        });
    }

    public function setFullNameAttribute($value): void
    {
        $this->attributes['full_name'] = $value;
        // Only split if first/last not explicitly provided in the current request fill
        if (!array_key_exists('firstName', $this->attributes) || !array_key_exists('lastName', $this->attributes)) {
            $this->splitFullName($value);
        }
    }

    public function getFullNameAttribute($value): string
    {
        if ($value) {
            return $value;
        }
        $parts = trim(($this->attributes['firstName'] ?? '') . ' ' . ($this->attributes['lastName'] ?? ''));
        return $parts;
    }

    protected function splitFullName(?string $fullName): void
    {
        if (!$fullName) {
            return;
        }
        $segments = preg_split('/\s+/', trim($fullName));
        if (count($segments) === 1) {
            $this->attributes['firstName'] = $segments[0];
            $this->attributes['lastName'] = $segments[0];
        } else {
            $this->attributes['firstName'] = array_shift($segments);
            $this->attributes['lastName'] = implode(' ', $segments);
        }
    }

    protected function synchronizeNameParts(): void
    {
        // If full_name present but one part missing, split
        if (($this->attributes['full_name'] ?? null) && (!($this->attributes['firstName'] ?? null) || !($this->attributes['lastName'] ?? null))) {
            $this->splitFullName($this->attributes['full_name']);
        }

        // If both parts exist but full_name empty, compose
        if ((!$this->attributes['full_name'] ?? true) && ($this->attributes['firstName'] ?? null) && ($this->attributes['lastName'] ?? null)) {
            $this->attributes['full_name'] = trim($this->attributes['firstName'] . ' ' . $this->attributes['lastName']);
        }
    }
}
