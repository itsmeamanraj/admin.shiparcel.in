<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['wallets'] = WalletTransaction::with('user')->orderBy('id', 'desc')->paginate(10);
        return view('admin.wallets.index', $data);
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
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, WalletTransaction $wallet)
    {
        $request->validate([
            'status' => 'required|in:101,102,103,104,105',
        ]);
        if ($request->status == '101') {
            $walletData = Wallet::where('user_id', Auth::id())->first();
            if ($wallet && $walletData) {
                $walletData->update(
                    [
                        'amount' => $walletData->amount + $wallet->amount,
                        'user_id' => $wallet->user_id,
                    ]
                );
            } else {
                Wallet::create([
                    'amount' => $wallet->amount,
                    'user_id' => $wallet->user_id,
                    'status' => 1,
                    'promo_code' => null
                ]);
            }
        }
        $wallet->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Transaction status updated successfully.');
    }
    
}
