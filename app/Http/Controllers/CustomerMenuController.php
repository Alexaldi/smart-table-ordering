<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerMenuController extends Controller
{
    /**
     * Cari meja berdasarkan token QR.
     * Token ini berasal dari URL: /customer-menu/{token}
     */
    private function getTableByToken(string $token): DiningTable
    {
        return DiningTable::where('qr_token', $token)->firstOrFail();
    }

    /**
     * Membuat nama key session cart berdasarkan table_id.
     * Jadi cart Table 01 tidak campur dengan Table 02.
     */
    private function cartKey(DiningTable $table): string
    {
        return 'customer_cart_table_' . $table->id;
    }

    /**
     * Halaman utama customer menu.
     * Menampilkan kategori dan menu yang aktif, tersedia, dan stock > 0.
     */
    public function index(string $token)
    {
        $table = $this->getTableByToken($token);

        $categories = Category::with(['menuItems' => function ($query) {
            $query->where('is_active', true)
                ->where('is_available', true)
                ->where('stock', '>', 0)
                ->orderBy('name');
        }])
            ->orderBy('name')
            ->get();

        $cart = session()->get($this->cartKey($table), []);
        $cartCount = collect($cart)->sum('quantity');

        return view('customer.menu', compact(
            'table',
            'categories',
            'token',
            'cartCount'
        ));
    }

    /**
     * Halaman search customer.
     * Mencari menu berdasarkan nama atau deskripsi.
     */
    public function search(Request $request, string $token)
    {
        $table = $this->getTableByToken($token);
        $keyword = $request->query('q');

        $menuItems = MenuItem::with('category')
            ->where('is_active', true)
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('name')
            ->get();

        $cart = session()->get($this->cartKey($table), []);
        $cartCount = collect($cart)->sum('quantity');

        return view('customer.search', compact(
            'table',
            'menuItems',
            'keyword',
            'token',
            'cartCount'
        ));
    }

    /**
     * Halaman cart.
     * Data cart diambil dari session.
     */
    public function cart(string $token)
    {
        $table = $this->getTableByToken($token);

        $cart = session()->get($this->cartKey($table), []);

        $subtotal = collect($cart)->sum('subtotal');
        $grandTotal = $subtotal;
        $cartCount = collect($cart)->sum('quantity');

        return view('customer.cart', compact(
            'table',
            'cart',
            'subtotal',
            'grandTotal',
            'token',
            'cartCount'
        ));
    }

    /**
     * Tambah menu ke cart.
     * Kalau menu sudah ada di cart, quantity ditambah.
     */
    public function addToCart(Request $request, string $token)
    {
        $table = $this->getTableByToken($token);

        $validated = $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $menuItem = MenuItem::where('id', $validated['menu_item_id'])
            ->where('is_active', true)
            ->where('is_available', true)
            ->where('stock', '>', 0)
            ->firstOrFail();

        if ($validated['quantity'] > $menuItem->stock) {
            return back()->with('error', 'Stock menu tidak cukup.');
        }

        $cartKey = $this->cartKey($table);
        $cart = session()->get($cartKey, []);

        $menuItemId = $menuItem->id;

        if (isset($cart[$menuItemId])) {
            $newQuantity = $cart[$menuItemId]['quantity'] + $validated['quantity'];

            if ($newQuantity > $menuItem->stock) {
                return back()->with('error', 'Jumlah melebihi stock yang tersedia.');
            }

            $cart[$menuItemId]['quantity'] = $newQuantity;
            $cart[$menuItemId]['notes'] = $validated['notes'] ?? $cart[$menuItemId]['notes'];
            $cart[$menuItemId]['subtotal'] = $cart[$menuItemId]['price'] * $newQuantity;
        } else {
            $cart[$menuItemId] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'description' => $menuItem->description,
                'price' => (float) $menuItem->price,
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'] ?? null,
                'image_url' => $menuItem->image_url,
                'subtotal' => (float) $menuItem->price * $validated['quantity'],
            ];
        }

        session()->put($cartKey, $cart);

        return redirect()
            ->route('customer-menu.cart', ['token' => $token])
            ->with('success', 'Menu berhasil ditambahkan ke cart.');
    }

    /**
     * Update quantity menu di cart.
     */
    public function updateCart(Request $request, string $token)
    {
        $table = $this->getTableByToken($token);

        $validated = $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartKey = $this->cartKey($table);
        $cart = session()->get($cartKey, []);

        $menuItemId = $validated['menu_item_id'];

        if (! isset($cart[$menuItemId])) {
            return back()->with('error', 'Menu tidak ada di cart.');
        }

        $menuItem = MenuItem::findOrFail($menuItemId);

        if ($validated['quantity'] > $menuItem->stock) {
            return back()->with('error', 'Jumlah melebihi stock yang tersedia.');
        }

        $cart[$menuItemId]['quantity'] = $validated['quantity'];
        $cart[$menuItemId]['subtotal'] = $cart[$menuItemId]['price'] * $validated['quantity'];

        session()->put($cartKey, $cart);

        return back()->with('success', 'Cart berhasil diupdate.');
    }

    /**
     * Hapus menu dari cart.
     */
    public function removeFromCart(Request $request, string $token)
    {
        $table = $this->getTableByToken($token);

        $validated = $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
        ]);

        $cartKey = $this->cartKey($table);
        $cart = session()->get($cartKey, []);

        unset($cart[$validated['menu_item_id']]);

        session()->put($cartKey, $cart);

        return back()->with('success', 'Menu berhasil dihapus dari cart.');
    }

    /**
     * Checkout cart menjadi order.
     */
    public function checkout(Request $request, string $token)
    {
        $table = $this->getTableByToken($token);

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cartKey = $this->cartKey($table);
        $cart = session()->get($cartKey, []);

        if (empty($cart)) {
            return back()->with('error', 'Cart masih kosong.');
        }

        try {
            DB::transaction(function () use ($cart, $table, $validated, $cartKey) {
                $subtotal = collect($cart)->sum('subtotal');

                $order = Order::create([
                    'order_code' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'table_id' => $table->id,
                    'session_token' => session()->getId(),
                    'status' => 'pending',
                    'payment_method' => null,
                    'payment_status' => 'unpaid',
                    'subtotal' => $subtotal,
                    'discount_total' => 0,
                    'grand_total' => $subtotal,
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($cart as $item) {
                    $menuItem = MenuItem::lockForUpdate()->findOrFail($item['menu_item_id']);

                    if ($item['quantity'] > $menuItem->stock) {
                        throw ValidationException::withMessages([
                            'cart' => 'Stock ' . $menuItem->name . ' tidak cukup.',
                        ]);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'discount_id' => null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'discount_amount' => 0,
                        'subtotal' => $item['subtotal'],
                        'notes' => $item['notes'] ?? null,
                        'status' => 'pending',
                    ]);

                    $menuItem->decrement('stock', $item['quantity']);
                    $menuItem->refresh();

                    $menuItem->update([
                        'is_available' => $menuItem->stock > 0,
                    ]);
                }

                session()->forget($cartKey);
            });
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            return back()->with('error', 'Checkout gagal: ' . $exception->getMessage());
        }

        return redirect()
            ->route('customer-menu.index', ['token' => $token])
            ->with('success', 'Pesanan berhasil dibuat.');
    }
}
