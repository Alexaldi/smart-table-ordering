<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    public function index()
    {
        return view('customer.menu');
    }

    public function search()
    {
        return view('customer.search');
    }

    public function cart()
    {
        return view('customer.cart');
    }
}
