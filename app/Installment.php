<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    public $timestamps = false;
    protected $fillable = ['installment', 'amount', 'date', 'sale_id'];

    public function Sales()
    {
        return $this->belongsTo(Sale::class);
    }
}
