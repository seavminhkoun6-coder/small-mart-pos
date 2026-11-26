<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('category_name')->get();
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('product_name')
            ->get();

        return view('pos.index', compact('categories', 'products'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $totalAmount = 0;
                $preparedItems = [];

                foreach ($validated['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty = (int) $item['quantity'];

                    if ($product->stock < $qty) {
                        throw new \RuntimeException("ស្តុកទំនិញ «{$product->product_name}» មិនគ្រប់គ្រាន់ទេ (នៅសល់ {$product->stock})។");
                    }

                    $unitPrice = (float) ($product->selling_price ?? 0);
                    $subtotal = $unitPrice * $qty;
                    $totalAmount += $subtotal;

                    $preparedItems[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                $paidAmount = (float) $validated['paid_amount'];
                if ($paidAmount < $totalAmount) {
                    throw new \RuntimeException('ចំនួនប្រាក់ដែលទទួលបាន តិចជាងតម្លៃសរុប។');
                }

                $changeAmount = $paidAmount - $totalAmount;
                $invoiceNo = 'INV-' . now()->format('YmdHis') . '-' . rand(100, 999);

                $order = Order::create([
                    'invoice_no' => $invoiceNo,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $changeAmount,
                    'payment_method' => $validated['payment_method'] ?? 'cash',
                ]);

                foreach ($preparedItems as $row) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $row['product']->id,
                        'quantity' => $row['quantity'],
                        'unit_price' => $row['unit_price'],
                        'subtotal' => $row['subtotal'],
                    ]);

                    $row['product']->decrement('stock', $row['quantity']);
                }

                return $order->load('items.product');
            });

            return response()->json([
                'message' => 'ការលក់ត្រូវបានរក្សាទុកជោគជ័យ!',
                'order' => $order,
            ], 201);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function orders()
    {
        $orders = Order::with('items.product')
            ->latest()
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        return view('pos.orders', compact('orders', 'totalRevenue', 'totalOrders'));
    }
}