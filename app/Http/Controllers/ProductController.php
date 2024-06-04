<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $storeId)
    {
        $query = Product::where('store_id', $storeId);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(10);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        $productData = $request->validated();
        $product = $store->products()->create($productData);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($storeId, $id)
    {
        $product = Product::where('store_id', $storeId)
            ->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, $storeId, $id)
    {
        $product = Product::where('store_id', $storeId)
            ->whereHas('store', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);
        $product->update($request->validated());
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($storeId, $id)
    {
        $product = Product::where('store_id', $storeId)
            ->whereHas('store', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json(null, 204);
    }
}
