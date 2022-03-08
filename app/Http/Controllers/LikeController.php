<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if ($product->likes()->where('owner_id', Auth::id())->exists()) {
            $product->likes()->where('owner_id', Auth::id())->delete();
        } else {
            $product->likes()->create([
                'owner_id' => Auth::id(),
            ]);

        }
        return response()->json(null);
    }
}
