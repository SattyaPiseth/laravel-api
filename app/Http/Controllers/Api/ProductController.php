<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use IntlChar;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $files = Storage::disk('public')->allFiles('upload');

        $products = Products::orderBy('name', 'ASC');
        if ($request->has('search') && !empty($request->search)) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
        }
        $products = $products->paginate(10);
        if ($products->isEmpty()) {
            return response([
                "message" => "Products not found",
            ], 404);
        }
        $response = [
            'products' => $products,
            'files' => [],
        ];
        foreach ($files as $file) {
            $response['files'][] = [
                'name' => basename($file),
                'url' => Storage::disk('public')->url($file),
                'size' => round(Storage::disk('public')->size($file) / 1024 / 1024, 2) . 'MB',
                'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($file))->diffForHumans(),
                'extension' => pathinfo($file, PATHINFO_EXTENSION),
            ];
        }

        return response()->json($response);
    }


    /**
     * Show the form for creating a new resource.
     */


     public function create(Request $request)
     {
         $request->validate([
             'name' => 'required',
             'description' => 'required',
             'unit_price' => 'required',
             'cate_id' => 'required',
             'brands_id' => 'required',
             'image_url' => 'required|mimes:jpg,jpeg,png,csv,txt,xlsx,xls,pdf|max:2048',
         ]);

         $fileName = md5($request->file('image_url')->getClientOriginalName() . time()) . "." . $request->file('image_url')->getClientOriginalExtension();
         $filePath = $request->file('image_url')->storeAs('uploads', $fileName, 'public');

         if ($filePath) {
            $productData = [
                'uuid' => Uuid::uuid4()->toString(),
                'prod_code' => rand(100000000000, 999999999999),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'unit_price' => $request->input('unit_price'),
                'cate_id' => $request->input('cate_id'),
                'brands_id' => $request->input('brands_id'),
                'image_url' => [
                    'name' => basename($filePath),
                    'url' => Storage::disk('public')->url($filePath),
                    'size' => round(Storage::disk('public')->size($filePath) / 1024 / 1024, 2) . 'MB',
                    'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($filePath))->diffForHumans(),
                    'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
                ],
            ];


             $product = Products::create($productData);
             // Transform the created product instance into a resource
             $productResource = new ProductsResource($product);

             return response()->json(['message' => 'Product created successfully', 'product' => $productResource,
            ], 201);
         } else {
             return response()->json(['message' => 'Failed to upload product'], 500);
         }
     }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products=Product::find($id);
        return response()->json([
            'products'=>$products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

     public function delete(Request $request, string $id)
     {
         $product = Products::find($id);

         if (!$product) {
             return response()->json([
                 'message' => 'Product not found!',
             ], 404);
         }

         // Delete the product from the database
         $product->delete();

         // Check if the file parameter is present in the request
         if ($request->has('uploads')) {
             $fileName = $request->input('uploads');
             $filePath = 'uploads/' . $fileName;

             // Check if the file exists
             if (Storage::disk('public')->exists($filePath)) {
                 // Delete the file
                 Storage::disk('public')->delete($filePath);
                 return response()->json([
                     'message' => 'Product and file deleted successfully.',
                 ], 200);
             } else {
                 return response()->json([
                     'message' => 'File not found!',
                 ], 404);
             }
         }

         return response()->json([
             'message' => 'File parameter is missing.',
         ], 400);
     }



}
