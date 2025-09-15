<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory;

    // Table associée (optionnel car Laravel devine "audits")
    protected $table = 'audits';

    // Champs remplissables
    protected $fillable = [
        'etab_id',
        'fonct_id',
        'date_audit',
        'nb_detenus',
        'nb_edited_fingerprints',
        'nb_verified_fingerprints',
        'nb_without_fingerprints',
    ];
     public function etablissement()
    {
        return $this->belongsTo(Etablissements::class, 'etab_id');
    }

    public function fonctionnaire()
    {
        return $this->belongsTo(Fonctionnaire::class, 'fonct_id');
    }
    
    // relation Many-to-Many
    public function fonctionnaires()
    {
        return $this->belongsToMany(
            Fonctionnaire::class,
            'audit_fonctionnaire',   // nom de la table pivot
           // 'audit_id',              // clé locale dans la pivot
            //'fonctionnaire_id'       // clé étrangère cible dans la pivot
        )->withTimestamps();
    }
}
