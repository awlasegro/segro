<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserValletRequest;
use App\Models\User;
use App\Models\UserVallet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'id' => 'required|exists:users,id|unique:user_vallets,user_id',
            'valletAddress' => 'required|string|max:255',
            'valletType' => 'required|string|in:TRC20,ERC20,ETH,BTC',
            'blockchain' => 'required|string|in:TRON,Ethereum,Bitcoin,BSC',
        ]);

        $vallet = new UserVallet();
        $vallet->user_id = $request->input('id');
        $vallet->vallet_address = $request->input('valletAddress');
        $vallet->type = $request->input('valletType');
        $vallet->blockchain = $request->input('blockchain');
        // Phone is no longer collected on this form, but the column is
        // still NOT NULL — auto-generate a placeholder instead.
        $vallet->phone = 'N/A-' . strtoupper(Str::random(8));
        $vallet->save();

        return redirect('/administration')->with('walletUpdateSuccess', 'Wallet information created successfully.');
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
            'vallet_address' => 'required|string|max:255',
            'wallet_type' => 'required|string|in:TRC20,ERC20,ETH,BTC',
            'blockchain' => 'required|string|in:TRON,Ethereum,Bitcoin,BSC',
            'id' => 'required|exists:user_vallets,id',
        ]);

        // Retrieve the user by ID
        $user = UserVallet::findOrFail($request->input('id'));

        if (!$user) {
            return redirect()->back()->with('error', 'Wallet information not found.');
        }

        // Update the wallet information. Phone is no longer editable here —
        // whatever value the row already has (real or placeholder) is left
        // untouched.
        $user->vallet_address = $request->input('vallet_address');
        $user->type = $request->input('wallet_type');
        $user->blockchain = $request->input('blockchain');
        $user->save();

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
