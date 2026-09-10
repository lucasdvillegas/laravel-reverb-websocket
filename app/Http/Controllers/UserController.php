<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index(): Response
    {   
        $users = User::query()
            ->where('id', '!=', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        return Inertia::render('Users', [
            'users' => $users,
        ]);
    }
}
