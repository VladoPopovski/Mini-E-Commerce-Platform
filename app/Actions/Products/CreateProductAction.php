<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\Vendor;

class CreateProductAction
{
    public function execute(Vendor $vendor, array $data): Product
    {
        return $vendor->products()->create($data);
    }
}
