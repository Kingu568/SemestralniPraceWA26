<?php

class ListingDTO
{
    public $item_id;
    public $quality;
    public $quantity;
    public $price_per_unit;
    public $status;

    public function __construct($data)
    {
        $this->item_id = (int)($data['item_id'] ?? 0);
        $this->quality = trim($data['quality'] ?? 'NQ');
        $this->quantity = (int)($data['quantity'] ?? 0);
        $this->price_per_unit = (float)($data['price_per_unit'] ?? 0);
        $this->status = trim($data['status'] ?? 'active');

        if (!in_array($this->quality, ['NQ', 'HQ'], true)) {
            $this->quality = 'NQ';
        }
    }
}