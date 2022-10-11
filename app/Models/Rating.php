<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_teknisi',
        'phone_teknisi',
        'email_teknisi',
        'rate',
        'komentar',
        'photo_teknisi',
        'nilai',
        'user_id',
        'perbaikan_id',
        'room_id',
        'keluhan_id',
        'tanggal'
    ];

    public function room() {
        return $this->hasOne(Supply::class, 'id', 'room_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function keluhan() {
        return $this->hasOne(Keluhan::class, 'id', 'keluhan_id');
    }

    public function perbaikan() {
        return $this->hasOne(Perbaikan::class, 'id', 'perbaikan_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
