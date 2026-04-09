<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Database\Seeder;


class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendorData = [
            ['name' => 'Alice Chen',    'email' => 'alice@vendor.com'],
            ['name' => 'Bob Martinez',  'email' => 'bob@vendor.com'],
            ['name' => 'Clara Singh',   'email' => 'clara@vendor.com'],
            ['name' => 'David Okafor',  'email' => 'david@vendor.com'],
        ];

        foreach ($vendorData as $data) {
            $user = User::factory()->vendor()->create([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            $vendor = Vendor::factory()->create(['user_id' => $user->id]);

            // 5 products per vendor
            Product::factory()->count(5)->create(['vendor_id' => $vendor->id]);
        }
    }
}
