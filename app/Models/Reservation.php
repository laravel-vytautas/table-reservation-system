<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    const RESERVATION_TIMES = [
      "13:00",
      "14:00",
      "15:00",
      "16:00",
      "17:00",
      "18:00",
      "19:00",
      "20:00",
      "21:00",
      "22:00",
      "23:00",
      "24:00"
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "table_id",
        "reserved_by",
        "reserved_date",
        "reserved_time"
    ];

    public function reservedBy()
    {
        return $this->hasOne(User::class, 'id', 'reserved_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'reservation_users','reservation_id','user_id','id', 'id');
    }
}


