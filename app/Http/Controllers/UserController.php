<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortField = $request->query('sortField', 'id');
        $sortOrder = $request->query('sortOrder', 'asc');

        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orderBy($sortField, $sortOrder)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Employees', [
            'users' => $users,
            'filters' => $request->only(['search', 'sortField', 'sortOrder']),
        ]);
    }
}
