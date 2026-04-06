<?php

use App\Models\ProductCategory;
use App\Models\Product;

$cat = ProductCategory::create(['name' => 'Sản phẩm mới', 'slug' => 'san-pham-moi']);

Product::create([
    'category_id' => $cat->id,
    'name' => 'iPhone 15 Pro Max 256GB',
    'price' => 34990000,
    'sale_price' => 31990000,
    'stock' => 15,
    'sold' => 5,
    'status' => 'published',
    'thumbnail' => 'https://img.tgdd.vn/img-border/itgdd/products/v2/411/305658/iphone-15-pro-max-blue-1-600x600.jpg'
]);

Product::create([
    'category_id' => $cat->id,
    'name' => 'Samsung Galaxy S24 Ultra 5G 12GB/256GB',
    'price' => 33990000,
    'sale_price' => 28990000,
    'stock' => 10,
    'sold' => 8,
    'status' => 'published',
    'thumbnail' => 'https://img.tgdd.vn/img-border/itgdd/products/v2/439/319665/samsung-galaxy-s24-ultra-grey-1-600x600.jpg'
]);

Product::create([
    'category_id' => $cat->id,
    'name' => 'Tai nghe Bluetooth AirPods Pro Gen 2 MagSafe',
    'price' => 6190000,
    'sale_price' => 5850000,
    'stock' => 30,
    'sold' => 12,
    'status' => 'published',
    'thumbnail' => 'https://img.tgdd.vn/img-border/itgdd/products/v2/451/289663/airpods-pro-2-600x600.jpg'
]);

Product::create([
    'category_id' => $cat->id,
    'name' => 'MacBook Air 13 inch M2 2022 8GB/256GB',
    'price' => 27990000,
    'sale_price' => 25990000,
    'stock' => 20,
    'sold' => 3,
    'status' => 'published',
    'thumbnail' => 'https://img.tgdd.vn/img-border/itgdd/products/v2/444/282353/macbook-air-m2-midnight-1-600x600.jpg'
]);
