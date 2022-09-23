<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'perbaikan',
        'tanggal',
        'photo_perbaikan',
        'supply_id',
        'user_id',
        'keluhan_id',
        'rating',
        'komentar'
    ];

    public function supply() {
        return $this->hasOne(Supply::class, 'id', 'supply_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function keluhan() {
        return $this->hasOne(Keluhan::class, 'id', 'keluhan_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getPhotoPerbaikanAttribute($value)
    {
        return env('ASSET_URL'). "/uploads/".$value;
    }
}
