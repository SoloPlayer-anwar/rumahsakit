<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_toko',
        'phone',
        'alamat',
        'name_barang',
        'photo_product',
        'quantity',
        'harga',
        'total_harga',
        'photo_barcode',
    ];


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getPhotoProductAttribute($value)
    {
        return env('ASSET_URL'). "/uploads/".$value;
    }

    public function getPhotoBarcodeAttribute($value)
    {
        return env('ASSET_URL'). "/uploads/".$value;
    }
}
