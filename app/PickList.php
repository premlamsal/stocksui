<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickList extends Model
{
    public function pickListDetail()
    {
        return $this->hasMany('\App\PickListDetail', 'pick_list_id', 'id');
    }
   
}
