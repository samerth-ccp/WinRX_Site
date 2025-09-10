<?php


// app/Http/Controllers/Frontend/CookieCartController.php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Services\CookieCart;
use App\Models\Product;
use Illuminate\Http\Request;

class CookieCartController extends Controller
{
    public function __construct(private CookieCart $cart) {}

    public function show(Request $req)
    {
        $cookieCart = $this->cart->read($req);
        // Rehydrate for display (compute price safely server-side)
        $lines = [];
        foreach ($cookieCart['items'] as $key => $item) {
            try {
                $product = Product::findOrFail($item['product_id']);
                if($product->product_status == '0') {
                    unset($cookieCart['items'][$key]);
                } else {
                    $color = $product->color->find($item['meta']['color']);
                    $size = $item['meta']['size'];

                    // example pricing logic:
                    $prices = $product->product_price;
                    $unit = $prices[$color->color_id];

                    $lines[] = [
                        'key'          => $key,
                        'product_id'   => $product->product_id,
                        'product_name' => $product->product_name,
                        'product_image' => $product->product_image,
                        'product'    => ['id'=>$product->product_id,'name'=>$product->product_name],
                        'qty'        => $item['qty'],
                        'meta'       => $item['meta'] ?? [],
                        'color'     => $color->color_name,
                        'size'       => $size,
                        'unit_cents' => $unit,
                        'line_cents' => ($unit * $item['qty']),
                    ];
                }
            } catch (\Exception | Error $e) {

            }
        }

        $total = number_format((array_sum(array_column($lines, 'line_cents'))),2);

        $html = view('Frontend.cart.items',compact('lines'))->render();

        return response()->json([
            'currency' => 'USD',
            'items'    => $html,
            'total_cents' => $total,
            'count' =>  count($lines)
        ]);
    }

    public function trashcart(Request $req, CookieCart $cart)
    {
        $cart->clear(); // Clear the cart cookie
        return response()->json(['message' => 'Cart has been emptied.']);
    }

    public function add(Request $req)
    {
        $data = $req->validate([
            'product_id' => ['required','integer','exists:products,product_id'],
            'qty'        => ['required','integer','min:1','max:9999'],
            'meta'       => ['nullable','array'],
        ]);

        // Validate stock server-side
        $product = Product::findOrFail($data['product_id']);
        if($product->product_status  == '0') {
            return response()->json(['status'=>false,'data'=> '','message' => 'The selected product is not available.']);
        }
        if (!$product->product_status) abort(422, 'Unavailable');
        //if ($product->stock < $data['qty']) abort(422, 'Insufficient stock');
        $product_sizes = $product->size->toArray();
        $sizes = array_column($product_sizes,'size');
        if($product->product_status  == '0') {
            return response()->json(['status'=>false,'data'=> '','message' => 'The selected product is not available.']);
        }
        if(!in_array($data['meta']['size'],$sizes)) {
            return response()->json(['status'=>false,'data'=> '','message' => 'The selected product is not available in this size.']);
        }
        $product_colors = $product->color->toArray();
        $colors = array_column($product_colors,'color_id');
        if(!in_array($data['meta']['color'],$colors)) {
            return response()->json(['status'=>false,'data'=> '','message' => 'The selected product is not available in this color.']);
        }
        $cart = $this->cart->add($req, $data['product_id'], $data['qty'], $data['meta'] ?? []);

        return response()->json(['status'=>true,'data'=> $cart,'message' => 'Item added to cart.']);
    }

    public function updateQty(Request $req, string $key){
        $data = $req->validate(['qty' => ['required','integer','min:0','max:9999']]);

        // Optional: fetch product_id from cookie to re-check stock
        $cookieCart = $this->cart->read($req);
        $item = $cookieCart['items'][$key] ?? null;
        if (!$item) abort(404, 'Item not found');

        $product = \App\Models\Product::findOrFail($item['product_id']);
        //if ($data['qty'] > 0 && $product->stock < $data['qty']) abort(422, 'Insufficient stock');

        $cart = $this->cart->updateQty($req, $key, $data['qty']);
        return response()->json($cart);
    }

    public function remove(Request $req, string $key)
    {
        $cart = $this->cart->remove($req, $key);
        return response()->json($cart);
    }

    public function count(Request $req)
    {
        $cookieCart = $this->cart->read($req);
        foreach ($cookieCart['items'] as $key => $item) {
            try {
                $product = Product::findOrFail($item['product_id']);
                if($product->product_status == '0') {
                    unset($cookieCart['items'][$key]);
                }
            } catch(\Exception | Error $e) {
                unset($cookieCart['items'][$key]);
            }
        }
        $items = $cookieCart['items'] ?? [];
        $count = count($items);
        //array_sum(array_column($items, 'qty'));

        return response()->json(['count' => $count]);
    }
}
