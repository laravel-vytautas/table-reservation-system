<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "name",
        "table_count",
        "max_people_count",
    ];

    public function tables()
    {
        return $this->hasMany(Table::class, 'restaurant_id', 'id');
    }

}

