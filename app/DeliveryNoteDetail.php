<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryNoteDetail extends Model
{
    public function deliveryNote()
    {
        return $this->belongsTo('\App\DeliveryNote', 'delivery_note_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo('\App\Unit', 'unit_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo('\App\Product', 'product_id', 'id');
    }

    protected $fillable = ['delivery_note_id', 'product_id', 'product_name', 'quantity', 'unit_id', 'price', 'line_total'];

}
