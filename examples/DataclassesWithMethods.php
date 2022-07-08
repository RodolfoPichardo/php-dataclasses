<?php

require_once("../src/Dataclass.php");

use Dataclasses\Dataclass;

class PurchaseItem extends Dataclass {
    public int $id;
    public string $name;
    public int $quantity;
    public float $price;

    public function totalCost(): float {
        return $this->quantity * $this->price;
    }
}

$item = new PurchaseItem([
    "id" => 123,
    "name" => "Glass of water",
    "quantity" => 2,
    "price" => 0.99
]);

print($item->totalCost()); // 1.98