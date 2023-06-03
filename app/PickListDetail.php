<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickListDetail extends Model
{
    public function pickList()
    {
        return $this->belongsTo('\App\PickList', 'pick_list_id', 'id');
    }
}
