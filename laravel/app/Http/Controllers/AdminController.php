<?php

namespace App\Http\Controllers;

use App\Models\Payment;

class AdminController extends Controller
{
    public function payments()
    {
        $payments = Payment::latest()->get();

        return view('admin.payments.index', compact('payments'));
    }
}