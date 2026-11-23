<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    use Uuid;

    protected $guarded = ['id'];

    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }
}
