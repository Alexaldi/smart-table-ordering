<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestCommitController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Test commit dari HP berhasil',
            'status' => true,
        ]);
    }
}