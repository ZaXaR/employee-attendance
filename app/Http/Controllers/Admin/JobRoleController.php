<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRole;
use Illuminate\Http\Request;

class JobRoleController extends Controller
{
    public function index()
    {
        $roles = JobRole::orderBy('name')->get();
        return view('admin.job-roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.job-roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_roles,name',
        ]);

        JobRole::create(['name' => $request->name]);

        return redirect()->route('admin.job-roles.index')->with('success', 'Role created.');
    }

    public function edit(JobRole $jobRole)
    {
        return view('admin.job-roles.edit', compact('jobRole'));
    }

    public function update(Request $request, JobRole $jobRole)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:job_roles,name,' . $jobRole->id,
        ]);

        $jobRole->update(['name' => $request->name]);

        return redirect()->route('admin.job-roles.index')->with('success', 'Role updated.');
    }

    public function destroy(JobRole $jobRole)
    {
        $jobRole->delete();

        return redirect()->route('admin.job-roles.index')->with('success', 'Role deleted.');
    }
}