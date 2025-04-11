<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all users sorted by name
        $users = User::orderBy('admin', 'desc')
            ->orderBy('username', 'asc')
            ->get();

        return view('admin', ['users' => $users]);
    }
}
