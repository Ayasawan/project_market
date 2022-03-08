<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @var mixed
     */


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description ,
            'views'=>$this->views,
            'Expiraton date'=>$this->exp_date,
            'quantity'=>$this->quantity,
            'price'=>$this->price,
            'category_id'=>$this->category_id,
            'owner_id'=>$this->owner_id,
            'img_url' =>$this->img_url,
            'current_price' => $this->current_price,
            //when(!is_null($this->current_price),$this->current_price),


        ];
    }
}
