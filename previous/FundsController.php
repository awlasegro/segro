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
        $funds = new Funds();
        $funds->user_id = $request->input('id');
        $funds->amount = $request->input('amount');
        $funds->type = $request->input('type');
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
}
