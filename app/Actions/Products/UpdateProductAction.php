<?php

namespace App\Actions\Products;

use App\Models\Product;

class UpdateProductAction
{
    public function execute(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }
}
