<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etablissements extends Model
{ use HasFactory;

    // Nom de la table
    protected $table = 'etablissements';

    // Les colonnes autorisées à être "remplies" en masse
    protected $fillable = [
        'libelle',
    ];

    // Si tu veux, tu peux désactiver les timestamps si ta table n'a pas created_at / updated_at
    public $timestamps = false;
}
