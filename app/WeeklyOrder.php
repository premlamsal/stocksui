<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeeklyOrder extends Model
{
    public function WeeklyOrderDetailC()
    {
        return $this->hasMany('\App\WeeklyOrderDetailC', 'weekly_order_id', 'id');
    }
    public function WeeklyOrderDetailM()
    {
        return $this->hasMany('\App\WeeklyOrderDetailM', 'weekly_order_id', 'id');
    }
    public function WeeklyOrderDetailD()
    {
        return $this->hasMany('\App\WeeklyOrderDetailD', 'weekly_order_id', 'id');
    }

    protected $fillable = ['boat_name','note','store_id','date_order_requested','delivery_date'];

}
