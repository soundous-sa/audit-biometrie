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


    public $timestamps = false;
}
