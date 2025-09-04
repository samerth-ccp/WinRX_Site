<?php

// app/Services/CookieCart.php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CookieCart
{
    const COOKIE_NAME = 'cart';
    const MINUTES = 60 * 24 * 30; // 30 days
    const SESSION_SECURE_COOKIE = false; // 30 days

    public function read(Request $req): array
    {
        $cart = $req->cookie(self::COOKIE_NAME);
        if (!$cart) return ['currency' => 'USD', 'items' => []];

        $data = json_decode($cart, true);
        return is_array($data) ? $data : ['currency' => 'USD', 'items' => []];
    }

    public function write(array $cart): void
    {
        // keep tiny & deterministic ordering
        ksort($cart['items']);
        cookie()->queue(cookie(self::COOKIE_NAME, json_encode($cart), self::MINUTES, '/', null, self::SESSION_SECURE_COOKIE, true, false, 'Lax'));
    }

    public function key(int $productId, array $meta = []): string
    {
        // sort meta keys for stable key
        ksort($meta);
        $parts = [$productId];
        foreach ($meta as $k => $v) $parts[] = "$k:$v";
        return implode('|', $parts);
    }

    public function add(Request $req, int $productId, int $qty, array $meta = []): array
    {

        $cart = $this->read($req);
        $key  = $this->key($productId, $meta);

        $items = $cart['items'];
        $items[$key] = [
            'product_id' => $productId,
            'qty'        => ($items[$key]['qty'] ?? 0) + $qty,
            'meta'       => $meta
        ];

        $cart['items'] = $items;
        $this->write($cart);
        return $cart;
    }

    public function updateQty(Request $req, string $key, int $qty): array
    {
        $cart = $this->read($req);
        if ($qty < 1) {
            unset($cart['items'][$key]);
        } else {
            if (!isset($cart['items'][$key])) abort(404, 'Item not found');
            $cart['items'][$key]['qty'] = $qty;
        }
        $this->write($cart);
        return $cart;
    }

    public function remove(Request $req, string $key): array
    {
        $cart = $this->read($req);
        unset($cart['items'][$key]);
        $this->write($cart);
        return $cart;
    }

    public function clear(): void
    {
        cookie()->queue(cookie()->forget(self::COOKIE_NAME));
    }
}
