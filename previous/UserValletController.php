<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserValletRequest;
use App\Models\User;
use App\Models\UserVallet;
use Illuminate\Http\Request;

class UserValletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
{
    // Find the user by ID
    $user = User::findOrFail($id);

    // Get the associated wallet information
    $valletInformation = UserVallet::where('user_id', $user->id)->first();

    // Return the view with user and wallet information
    return view('admin.valletInformation', compact('user', 'valletInformation'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user = User::findOrFail($id);

        return view('admin.add-vallet-information', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'WalletAddress' => 'required',
        'WalletType' => 'required',
        'phone' => 'required',
        'blockchain' => 'required',
    ]);


    $vallet = new UserVallet();
    $vallet->user_id = $request->input('id');
    $vallet->vallet_address = $request->input('WalletAddress');
    $vallet->type = $request->input('WalletType');
    $vallet->blockchain = $request->input('blockchain');
    $vallet->phone = $request->input('phone');
    $vallet->save();

    return redirect('/administration')->with('walletAddeSuccess', 'Wallet information saved successfully.');
}

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->input('id');
$user = User::findOrFail($id);

// Retrieve wallet information using Eloquent
$walletInformation = UserVallet::where('user_id', $id)->first();

// Check if wallet information exists
if (!$walletInformation) {
    return redirect()->back()->with('error', 'Wallet information not found.');
}

// Return the wallet information to the Blade template
return view('admin.update-wallet-information', compact('walletInformation', 'user'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(UserVallet $userVallet)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserVallet $userVallet)
{
    $request->validate([
        'vallet_address' => 'required',
        'wallet_type' => 'required',
        'blockchain' => 'required',
        'phone' => 'required',
        'id' => 'required',
    ]);

    // Retrieve the wallet information by ID
    $wallet = UserVallet::findOrFail($request->input('id'));

    // Update the wallet information
    $wallet->vallet_address = $request->input('vallet_address');
    $wallet->type = $request->input('wallet_type');
    $wallet->blockchain = $request->input('blockchain');
    $wallet->phone = $request->input('phone');
    $wallet->save();

    // Redirect back with a success message
    return redirect('/administration')->with('walletUpdateSuccess', 'Wallet information updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserVallet $userVallet)
    {
    }
}
