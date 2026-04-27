<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()
            ->cart()
            ->firstOrCreate()
            ->load('items.product');

        $totalAmount = $cart->items->sum(fn (CartItem $item) => $item->quantity * (float) $item->product->price);

        return view('cart.index', [
            'cart' => $cart,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $cart = $request->user()->cart()->firstOrCreate();

        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = min($product->stock, ($item?->quantity ?? 0) + $validated['quantity']);

        if ($item) {
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => min($product->stock, $validated['quantity']),
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->cart->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->update([
            'quantity' => min($cartItem->product->stock, $validated['quantity']),
        ]);

        return back()->with('success', 'Cart quantity updated.');
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_unless($cartItem->cart->user_id === $request->user()->id, 403);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
