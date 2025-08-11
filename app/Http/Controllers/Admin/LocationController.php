<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Location::create(['name' => $request->name]);

        return redirect()->route('admin.locations.index')->with('success', 'Location created.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        // Toggle status if requested
        if ($request->has('toggle')) {
            $location->is_active = !$location->is_active;
            $location->save();

            return redirect()->route('admin.locations.index')->with('success', 'Location status updated.');
        }

        // Regular update
        $request->validate(['name' => 'required|string|max:255']);
        $location->update(['name' => $request->name]);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted.');
    }
}