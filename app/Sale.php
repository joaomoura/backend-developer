<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public $timestamps = false;
    protected $fillable = ['id','date','amount','customer','street','neighborhood','city','state','postal_code','installments'];

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

}
