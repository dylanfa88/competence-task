<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public int $coffee_id;
    public float $quantity;
    public float $unit_cost;
    public float $selling_price;

    protected $fillable = [
        'coffee_id',
        'quantity',
        'unit_cost',
        'selling_price'
    ];
}
