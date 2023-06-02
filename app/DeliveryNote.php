<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    
    public function deliveryNoteDetail()
    {
        return $this->hasMany('\App\DeliveryNoteDetail', 'delivery_note_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('\App\Supplier', 'supplier_id', 'id');
    }

    protected $fillable = ['delivery_note_date','delivery_note_reference_id', 'due_date', 'image', 'supplier_id', 'supplier_name', 'sub_total', 'discount', 'grand_total', 'status', 'store_id', 'custom_delivery_note_id', 'note'];

}
