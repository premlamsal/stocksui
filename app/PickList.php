<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickList extends Model
{
    public function pickListDetail()
    {
        return $this->hasMany('\App\PickListDetail', 'pick_list_id', 'id');
    }
    protected $fillable = ['sailing_date','picked_date', 'date', 'date_ordered', 'picked_by', 'checked_by', 'missing', 'ship_name', 'ship_address', 'image', 'pick_list_reference_id', 'total_quantity_to_pick', 'total_items_on_pick_list','status','store_id'];

}
