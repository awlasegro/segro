<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReferenceCodesRequest;
use App\Models\ReferenceCodes;
use Illuminate\Http\Request;

class ReferenceCodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refCodes = ReferenceCodes::all();

        return view('admin.reference-codes', compact('refCodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-reference-code');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $refCode = new ReferenceCodes();
        $refCode->code = $request->input('reference_code');
        $refCode->description = $request->input('description');
        $refCode->save();

        return redirect('reference-codes');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ReferenceCodes $referenceCodes)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ReferenceCodes $referenceCodes)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateReferenceCodesRequest $request, ReferenceCodes $referenceCodes)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReferenceCodes $referenceCodes)
    {
    }
}
