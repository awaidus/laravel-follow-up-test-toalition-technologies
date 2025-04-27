<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $path = public_path('products.json');

        if (!file_exists($path)) {
            file_put_contents($path, json_encode([]));
        }

        $data = json_decode(file_get_contents($path), true) ?? [];


        return inertia('Products/Index', [
            'products' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'qty_in_stock' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $path = public_path('products.json');

        $products = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

        $products[] = [
            'id' => now()->toString(),
            'product_name' => $request->product_name,
            'qty_in_stock' => (int) $request->qty_in_stock,
            'price' => (float) $request->price,
            'created_at' => now()->toDateTimeString(),
        ];

        file_put_contents($path, json_encode($products, JSON_PRETTY_PRINT));

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $path = public_path('products.json');

        $products = json_decode(file_get_contents($path), true);

        foreach ($products as &$product) {
            if ($product['id'] == $id) {
                $product['product_name'] = $request->product_name;
                $product['qty_in_stock'] = (int) $request->qty_in_stock;
                $product['price'] = (float) $request->price;
                break;
            }
        }

        file_put_contents($path, json_encode($products, JSON_PRETTY_PRINT));

        return redirect()->back();
    }
}
