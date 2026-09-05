<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memberships = Membership::all();
        return view('admin.memberships', compact('memberships'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-membership');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'membership' => 'required|string|max:255',
            'order_limit' => 'required|integer',
            'commission' => 'required|numeric',
        ]);

        $membershipLevel = new Membership();
        $membershipLevel->level_name = $request->input('membership');
        $membershipLevel->order_limit = $request->input('order_limit');
        $membershipLevel->commission = $request->input('commission');
        $membershipLevel->save();

        return redirect('memberships')->with('success', 'Membership level created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $membership = Membership::findOrFail($id);
        return view('admin.edit-membership', compact('membership'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'membership' => 'required|string|max:255',
            'order_limit' => 'required|integer',
            'commission' => 'required|numeric',
        ]);

        $membership = Membership::findOrFail($id);
        $membership->level_name = $request->input('membership');
        $membership->order_limit = $request->input('order_limit');
        $membership->commission = $request->input('commission');
        $membership->save();

        return redirect('memberships')->with('success', 'Membership level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();

        return redirect('memberships')->with('success', 'Membership level deleted successfully.');
    }
}
