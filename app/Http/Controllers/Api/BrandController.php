<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brand = Brand::orderby('brand_name', 'ASC');
        if ($request->has('search') &&!empty($request->search)) {
            $brand = $brand->where('brand_name', 'like', '%'. $request->search. '%');
        }
        $brand = $brand->paginate(10);
        if ($brand->isEmpty()) {
            return response()->json([
                "message" => "Brand not found",
            ], 404);
        }
        return response()->json(['brand' => $brand], 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    //  $uuid = Uuid::uuid4();
     $data =$request->validate([
        '*.brand_name'=>'required|string|max:255',
     ]);
     foreach($data as $brandData){
        $brandData['brand_uuid'] = Uuid::uuid4()->toString();
        $brand[]=Brand::create($brandData);
     }
     return response()->json(['brand' => $brand], 201);
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
    public function show($id)
    {
        $brand =Brand::find($id);
        return response()->json([
            'brand'=>$brand
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
        $data = $request->validate([
            'brand_name'=>'required|string'
        ]);
        if(isset($data)){
            $brand = Brand::find($id);
            $brand->update($data);
            if (!$brand) {
                return response()->json([
                   'message' => 'Brand not found'
                ], 404);
            } else {
                return response()->json([
                   'message' => 'Brand have been updated successfully',
                    'data' => new BrandResource($brand)
                ], 201);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id , Brand $brand)
    {
        $brand =$brand->find($id);
        if (!$brand) {
            return response()->json([
              'message' => 'Brand not found',
            ]);
        }
        $brand->delete();

        return response()->json([
          'message' => 'Brand has been deleted',
        ]);
    }
}
