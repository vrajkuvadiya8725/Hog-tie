<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Your cart is empty.');
        }

        return view('checkout.create', [
            'cart' => $cart,
            'addresses' => $request->user()->addresses()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'selected_address' => ['required', 'string'],
            'label' => ['nullable', 'string', 'max:100'],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cod,razorpay'],
            'razorpay_payment_id' => ['nullable', 'string'],
        ]);

        // 🔹 Address handle
        if (str_starts_with($validated['selected_address'], 'existing:')) {
            $addressId = (int) str_replace('existing:', '', $validated['selected_address']);
            $address = $request->user()->addresses()->findOrFail($addressId);
        } else {
            $required = $request->validate([
                'recipient_name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:25'],
                'address_line' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:120'],
                'state' => ['required', 'string', 'max:120'],
                'postal_code' => ['required', 'string', 'max:20'],
            ]);

            $address = $request->user()->addresses()->create([
                'label' => $validated['label'] ?: 'Address',
                ...$required,
            ]);
        }

        // 🔥 Order create
        $order = DB::transaction(function () use ($request, $cart, $address, $validated) {

            $totalQty = 0;
            $totalAmount = 0;

            $order = Order::create([
                'user_id' => $request->user()->id,
                'address_id' => $address->id,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'address_line' => $address->address_line,
                'city' => $address->city,
                'state' => $address->state,
                'postal_code' => $address->postal_code,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'razorpay' ? 'paid' : 'pending',
                'payment_reference' => $validated['razorpay_payment_id'] ?? null,
                'status' => 'placed',
            ]);

            foreach ($cart->items as $item) {
                $price = (float) $item->product->price;
                $lineTotal = $price * $item->quantity;

                $totalQty += $item->quantity;
                $totalAmount += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_image' => $item->product->image_path,
                    'quantity' => $item->quantity,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update([
                'total_quantity' => $totalQty,
                'total_amount' => $totalAmount,
            ]);

            // clear cart
            $cart->items()->delete();

            return $order;
        });

        // 🔥 REDIRECTION LOGIC
        if ($order->payment_method === 'razorpay') {
            return redirect('/')->with('success', 'Payment Successful!');
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order placed with COD.');
    }
}