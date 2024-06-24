<?php

namespace App\DTO;

class SubscriptionBundleDTO
{
    public int $id;
    public float $price;
    public bool $canBuy;
    public int $duration;
    public int $discount;

    /**
     * @param mixed $data
     */
    public function __construct(mixed $data)
    {
        $this->id = $data['id'];
        $this->price = $data['price'];
        $this->duration = $data['duration'];
        $this->canBuy = $data['canBuy'];
        $this->discount = $data['discount'];
    }
}
