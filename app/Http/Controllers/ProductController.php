<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $products = Product::all();
        $response = [
            'message' => 'List of all products',
            'data' => $products
        ];
        return response()->json($response, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $product = Product::create($input);
        $response = [
            'message' => 'Product created successfully',
            'data' => $product
        ];
        return response()->json($response, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::findOrFail($id);
        $response = [
            'message' => 'Product details',
            'data' => $product
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $product = Product::findOrFail($id);
        $product->update($input);
        $response = [
            'message' => 'Product updated successfully',
            'data' => $product
        ];
        return response()->json($response, 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();
        $response = [
            'message' => 'Product deleted successfully',
        ];
        return response()->json($response, 200);
    }
}
