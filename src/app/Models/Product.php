<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const LOW_STOCK_THRESHOLD = 10;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
