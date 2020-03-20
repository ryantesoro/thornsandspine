<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = "promotions";

    protected $fillable = [
        'name', 'file_name'
    ];

    public $timestamps = false;

    public function getPromotions()
    {
        $promotions = Promotion::select('*')
            ->get();

        return $promotions;
    }

    public function storePromotion($promotion_details)
    {
        $store_promotion = Promotion::create($promotion_details);

        return $store_promotion;
    }

    public function getPromotion($promotion_id)
    {
        $promotion = Promotion::where('id', $promotion_id)
            ->get()
            ->first();

        return $promotion;
    }

    public function deletePromotion($promotion_id)
    {
        $delete_promotion = Promotion::where('id', $promotion_id)
            ->delete();

        return $delete_promotion;
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
