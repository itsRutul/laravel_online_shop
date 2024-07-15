<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    // Show the form for creating a new country
    public function create()
    {
        return view('admin.country.create');
    }

    // Store a newly created country in the database
    public function store(Request $request)
    {
        $request->validate([
            'countryname' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Country::create([
            'name' => $request->countryname,
            'status' => $request->status,
        ]);

        return redirect()->route('countries.index')->with('success', 'Country created successfully.');
    }

    // Display a listing of the countries
    public function index(Request $request)
    {
        $query = Country::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $countries = $query->paginate(10); // Adjust the number of items per page as needed

        return view('admin.country.index', compact('countries'));
    }

    // Show the form for editing the specified country
    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.country.edit', compact('country'));
    }

    // Update the specified country in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $country = Country::findOrFail($id);
        $country->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }

    // Remove the specified country from the database
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();

        return redirect()->route('countries.index')->with('success', 'Country deleted successfully.');
    }
}
