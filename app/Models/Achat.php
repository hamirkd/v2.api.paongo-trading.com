<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'nomprenom',
       'telephone',
       'emailadresse',
       'titre',
       'montant',
   ];
   protected $attributes = [
    'moyenpaiement' => '',
    'adressepaiement' => '',
    'montantcrypto' => '',
    'montantcrypto_recu' => '',
    'transaction_code' => '',
    'transaction_hash' => '',
    'date_paiement' => null,
    'etat_investissement' => '',
    'supprimer' => 0
];
}
