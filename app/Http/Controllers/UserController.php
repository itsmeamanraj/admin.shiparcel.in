<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\CourierCompany;
use App\Models\CourierWeightSlab;
use App\Models\User;
use App\Models\UserCourierWeightSlab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $data['users'] = $query->orderBy('id', 'desc')->paginate(10);

        $data['users']->appends([
            'search' => $request->search,
            'status' => $request->status,
        ]);

        return view('admin.users.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(CreateUserRequest $request)
    {
        // Validate and create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'website_url' => $request->website_url,
            'billing_address' => $request->billing_address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'image_url' => $request->file('image_url') ? $request->file('image_url')->store('users', 'public') : null,
            'status' => $request->status,
            'cod_charges' => $request->cod_charges,
            'cod_percentage' => $request->cod_percentage,
            'chargeable_amount' => $request->chargeable_amount,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id); // Retrieve user or fail if not found
        return view('admin.users.edit', compact('user')); // Pass user data to the edit view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Update user details
        $user->update([
            'name' => $request->name,
            'website_url' => $request->website_url,
            'billing_address' => $request->billing_address,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'state' => $request->state,
            'status' => $request->status,
            'cod_charges' => $request->cod_charges,
            'cod_percentage' => $request->cod_percentage,
            'chargeable_amount' => $request->chargeable_amount
        ]);

        // If a new password is provided, update it
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Handle profile image upload
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('users', 'public');
            $user->update(['image_url' => $imagePath]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show Update User Charge Update form
     */
    public function userCharges(Request $request)
    {
        $users = User::where('status', 1)->orderBy('id', 'desc')->paginate('10');
        return view('admin.users.user_charges', compact('users'));
    }


    /**
     * Update User Chargeable Amount
     */

    public function updateUserCharges(Request $request, $userId)
    {
        $request->validate([
            'chargeable_amount' => ['required', 'numeric'],
        ]);

        $user = User::findOrFail($userId);
        $user->update(['chargeable_amount' => $request->chargeable_amount]);

        return redirect()->back()->with('success', 'Chargeable Amount updated successfully.');
    }
}
