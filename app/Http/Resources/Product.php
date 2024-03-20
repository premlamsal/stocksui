<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'product_name' => $this->name,
            'unit' => $this->unit,
            'image' => $this->image,
            'category' => $this->category,
            'category_name' => $this->category->name,
            'store_id' => $this->store_id,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'product_cat_id' => $this->product_cat_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'custom_product_id' => $this->custom_product_id,
            'low_stock_alert_active' => $this->low_stock_alert_active,
            'low_stock_alert_quantity' => $this->low_stock_alert_quantity,

        ];
    }
}
