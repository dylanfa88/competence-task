<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public float $quantity;
    public float $unit_cost;
    public float $selling_price;

    protected $fillable = [
        'quantity',
        'unit_cost',
        'selling_price'
    ];
}
