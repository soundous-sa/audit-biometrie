<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'audits';

    // Champs remplissables
    protected $fillable = [
        'etab_id',
        'date_audit',
        'nb_detenus',
         'fix_fingerprints',
        'nb_edited_fingerprints',
        'nb_verified_fingerprints',
        'response_type_id',
        'nb_without_fingerprints',
        'full_finger_percent',
    ];

    
    public function etablissement()
    {
        return $this->belongsTo(Etablissements::class, 'etab_id');
    }


    // relation Many-to-Many
    public function fonctionnaires()
    {
        return $this->belongsToMany(
            Fonctionnaire::class,
            'audit_fonctionnaire',   // nom de la table pivot
             'audit_id',              // clé locale dans la pivot
            'fonctionnaire_id'       // clé étrangère cible dans la pivot
        )->withTimestamps();
    }
    public function responseType()
    {
        return $this->belongsTo(ResponseType::class);
    }
    protected static function booted()
    {
        static::saving(function ($audit) {
            $edited   = $audit->nb_edited_fingerprints ?? 0;
            $verified = $audit->nb_verified_fingerprints ?? 0;
            $fix_fingerprints = $audit->fix_fingerprints ?? 0;
            $totalFingers = $verified + $edited + $fix_fingerprints;

            $audit->full_finger_percent = $totalFingers > 0
                ? round(($verified / $totalFingers) * 100, 2)
                : 0;
        });
    }

}
