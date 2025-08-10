<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;


class UserController extends Controller
{
    public function dashboard(): View
    {
        // Basic stats for the header cards
        $stats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'members' => User::where('is_admin', false)->count(),
            'new_last_7' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Latest users for quick overview
        $users = User::latest()->paginate(10);

        return view('admin.dashboard', compact('stats', 'users'));
    }

    public function index()
    {
        // List all users with pagination
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Show form to create a new user
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'sometimes|boolean',
        ]);

        // Hash password before saving
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->has('is_admin');

        // Create new user
        User::create($validated);
        return redirect(RouteServiceProvider::ADMIN_HOME)->with('success', 'User created successfully');

    }

    public function edit(User $user)
    {
        // Show edit form
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Validate update data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_admin' => 'sometimes|boolean',
        ]);

        // Update password if provided
        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->has('is_admin');

        // Update user
        $user->update($validated);

        return redirect(RouteServiceProvider::ADMIN_HOME)->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect(RouteServiceProvider::ADMIN_HOME)->with('success', 'User deleted successfully');
    }

}
