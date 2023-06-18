<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeeklyOrderDetailD extends Model
{
    public function WeeklyOrder()
    {
        return $this->belongsTo('\App\WeeklyOrder', 'weekly_order_id', 'id');
    }
    protected $fillable = ['shelf_code','product_name','checked','quantity','picked'];

}
