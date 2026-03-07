<?php
namespace App\Http\Controllers;

use App\Models\order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Get all orders with user info
    public function index()
    {
        $orders = order::with('users', 'orderItems')->orderBy('id', 'asc')->get();
        return response()->json(['orders' => $orders]);
    }

    // Get all orders for a specific user
    public function userOrders($userId)
    {
        $orders = order::with('orderItems')->where('user_id', $userId)->orderBy('id', 'asc')->get();
        return response()->json(['orders' => $orders]);
    }
}
