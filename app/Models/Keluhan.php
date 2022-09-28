<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    use HasFactory;

   /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_id',
        'tanggal',
        'kendala',
        'photo_kendala',
        'status',
        'user_id',
        'name_teknisi',
        'token',
        'photo_teknisi',
        'phone_teknisi'
    ];

    public function room() {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


   public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getPhotoKendalaAttribute($value)
    {
        return env('ASSET_URL'). "/uploads/".$value;
    }
}

