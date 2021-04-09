<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'listed',
        'available',
    ];

    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function categories() {
    	return $this->hasMany(ProductCategory::class);
    }

    public function images() {
    	return $this->hasMany(ProductImage::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function loans() {
        return $this->hasMany(ProductLoan::class);
    }

    public function is_available() {
        if ($this->user->blocked) return 0;
        $loan = $this->loans->last();
        if ($loan && !$loan->returned) {
            return 0;
        }
        return $this->available;
    }
}
