<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class UserController extends Controller
{
    public function dashboard(): View
    {
        // Header stats (active vs suspended breakdown)
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_suspended', false)->count(),
            'suspended' => User::where('is_suspended', true)->count(),
            'admins' => User::where('is_suspended', false)->where('is_admin', true)->count(),
            'members' => User::where('is_suspended', false)->where('is_admin', false)->count(),
            'new_last_7' => User::where('is_suspended', false)->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Latest users; eager-load to avoid N+1 if jobRole is shown
        $users = User::with('jobRole')->latest()->paginate(10);

        return view('admin.dashboard', compact('stats', 'users'));
    }

    public function index(Request $request): View
    {
        // Optional filter by status: all|active|suspended (default all)
        $status = $request->query('status', 'all');

        $users = User::with('jobRole')
            ->when($status === 'active', fn($q) => $q->where('is_suspended', false))
            ->when($status === 'suspended', fn($q) => $q->where('is_suspended', true))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends(['status' => $status]);

        return view('admin.users.index', compact('users', 'status'));
    }

    public function create(): View
    {
        $roles = JobRole::all();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', // add |confirmed if you add confirmation field
            'phone' => 'nullable|string|max:20',
            'job_role_id' => 'nullable|exists:job_roles,id',
            'is_admin' => 'sometimes|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_admin'] = $request->boolean('is_admin');
        $validated['is_suspended'] = false; // new accounts start active

        User::create($validated);

        return redirect(RouteServiceProvider::ADMIN_HOME)->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $roles = JobRole::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', // add |confirmed if you add confirmation field
            'phone' => 'nullable|string|max:20',
            'job_role_id' => 'nullable|exists:job_roles,id',
            'is_admin' => 'sometimes|boolean',
        ]);

        // Handle password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_admin'] = $request->boolean('is_admin');

        // Prevent self-demotion (avoid locking yourself out)
        if (auth()->id() === $user->id && $user->is_admin && !$validated['is_admin']) {
            return back()->with('error', 'You cannot revoke your own admin rights.')->withInput();
        }

        // Prevent demoting the last active admin
        if ($user->is_admin && !$validated['is_admin']) {
            $otherActiveAdmins = User::where('id', '!=', $user->id)
                ->where('is_admin', true)
                ->where('is_suspended', false)
                ->count();

            if ($otherActiveAdmins === 0) {
                return back()->with('error', 'You cannot demote the last active admin.')->withInput();
            }
        }

        $user->update($validated);

        return redirect(RouteServiceProvider::ADMIN_HOME)->with('success', 'User updated successfully.');
    }

    public function suspend(User $user)
    {
        // Prevent self-suspend
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        // Prevent suspending the last active admin
        if ($user->is_admin) {
            $otherActiveAdmins = User::where('id', '!=', $user->id)
                ->where('is_admin', true)
                ->where('is_suspended', false)
                ->count();

            if ($otherActiveAdmins === 0) {
                return back()->with('error', 'You cannot suspend the last active admin.');
            }
        }

        $user->update(['is_suspended' => true]);

        // Optional: revoke API tokens if you use Sanctum
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        return back()->with('success', 'User suspended.');
    }

    public function resume(User $user)
    {
        $user->update(['is_suspended' => false]);

        return back()->with('success', 'User resumed.');
    }
}