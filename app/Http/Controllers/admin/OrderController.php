<?php

// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10); // Fetch orders with pagination
        return view('admin.orders.index', compact('orders'));
    }

    public function details($id)
    {
        $order = Order::with('orderItems')->findOrFail($id); // Fetch a single order with its items
        return view('admin.orders.details', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

        // Send the email
        invoiceEmail($id);

        return redirect()->route('orders.details', $id)->with('success', 'Order status updated and invoice email sent.');
    }
}
