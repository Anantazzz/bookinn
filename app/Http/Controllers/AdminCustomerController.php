<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'user')
                        ->withCount('reservasis')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('admin.customers.index', compact('customers'));
    }
    
    public function show($id)
    {
        $customer = User::where('role', 'user')
                       ->with(['reservasis.kamar.hotel', 'reservasis.pembayaran'])
                       ->findOrFail($id);
        
        return view('admin.customers.show', compact('customer'));
    }
}