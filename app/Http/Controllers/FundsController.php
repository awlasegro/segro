<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateFundsRequest;
use App\Models\Funds;
use App\Models\User;
use Illuminate\Http\Request;

class FundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = User::findOrFail($id);

        return view('admin.add-debit', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:deposit,withdrawal,commission',
        ]);

        $funds = new Funds();
        $funds->user_id = $request->input('id');
        $funds->amount = $request->input('amount');
        $funds->type = $request->input('type');
        $funds->status = 'active'; // Admin created transactions are active
        $funds->save();

        return redirect('/administration')->with('funds_success', 'Your funds has been successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Funds $funds)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Funds $funds)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFundsRequest $request, Funds $funds)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funds $funds)
    {
    }

    public function depositRequests()
    {
        $requests = Funds::with('user')
            ->where('type', 'deposit')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.deposit-requests', compact('requests'));
    }

    public function redemptionRequests()
    {
        $requests = Funds::with('user')
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Bind wallet address details for each request
        foreach ($requests as $req) {
            $req->wallet = \App\Models\UserVallet::where('user_id', $req->user_id)->first();
        }

        return view('admin.redemption-requests', compact('requests'));
    }

    public function approveRequest($id)
    {
        $fund = Funds::findOrFail($id);
        $fund->status = 'active';
        $fund->save();

        return redirect()->back()->with('success', 'Request approved successfully.');
    }

    public function rejectRequest($id)
    {
        $fund = Funds::findOrFail($id);
        $fund->status = 'rejected';
        $fund->save();

        return redirect()->back()->with('success', 'Request rejected/declined.');
    }
}
