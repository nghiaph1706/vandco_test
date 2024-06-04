<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCreateRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Store::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $stores = $query->paginate(10);
        
        return response()->json($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreateRequest $request)
    {
        $storeData = $request->validated();
        $store = Auth::user()->stores()->create($storeData);
        return response()->json($store, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = Store::findOrFail($id);
        return response()->json($store);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, $id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);
        $store->update($request->validated());
        return response()->json($store);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);
        $this->authorize('delete', $store);

        $store->delete();

        return response()->json(null, 204);
    }
}
