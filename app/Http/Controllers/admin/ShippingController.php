<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $shippings = ShippingCharge::with('country')
            ->when($search, function($query, $search) {
                return $query->whereHas('country', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })
            ->paginate(10);

        return view('admin.shipping.index', compact('shippings'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('admin.shipping.create', compact('countries'));
    }

    public function store(Request $request)
    {
        \Log::info('Store request data: ', $request->all());
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'amount' => 'required|numeric',
        ]);
    
        // Check if a shipping charge already exists for the selected country
        $existingShippingCharge = ShippingCharge::where('country_id', $request->country_id)->first();
        if ($existingShippingCharge) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping charge already exists for the selected country.'
            ], 422); // Unprocessable Entity status code
        }
    
        ShippingCharge::create([
            'country_id' => $request->country_id,
            'amount' => $request->amount,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Shipping charge created successfully.'
        ]);
    }
    


    

    public function edit($id)
    {
        $shipping = ShippingCharge::findOrFail($id);
        $countries = Country::all();
        return view('admin.shipping.edit', compact('shipping', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'amount' => 'required|numeric',
        ]);

        $shipping = ShippingCharge::findOrFail($id);
        $shipping->update([
            'country_id' => $request->country_id,
            'amount' => $request->amount,
        ]);

        return redirect()->route('shippings.index')->with('success', 'Shipping charge updated successfully.');
    }

    public function destroy($id)
    {
        $shipping = ShippingCharge::findOrFail($id);
        $shipping->delete();

        return redirect()->route('shippings.index')->with('success', 'Shipping charge deleted successfully.');
    }
}
