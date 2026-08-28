<?php

namespace App\Http\Controllers;

use App\Models\ReferenceCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferenceCodesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refCodes = ReferenceCodes::with('usedBy')->orderBy('id', 'desc')->get();

        return view('admin.reference-codes', compact('refCodes'));
    }

    /**
     * Generate a new reference code with one click (used by the "Generate
     * Reference Code" button/modal) instead of the admin typing one in.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (ReferenceCodes::where('code', $code)->exists());

        $refCode = new ReferenceCodes();
        $refCode->code = $code;
        $refCode->save();

        return response()->json(['code' => $refCode->code]);
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
        $request->validate([
            'reference_code' => 'required|string|max:255|unique:reference_codes,code',
        ]);

        $refCode = new ReferenceCodes();
        $refCode->code = $request->input('reference_code');
        $refCode->save();

        return redirect('reference-codes')->with('ref_code_success', 'Reference code created successfully.');
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
    public function edit($id)
    {
        $refCode = ReferenceCodes::findOrFail($id);

        return view('admin.edit-reference-code', compact('refCode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $refCode = ReferenceCodes::findOrFail($id);

        $request->validate([
            'reference_code' => 'required|string|max:255|unique:reference_codes,code,' . $refCode->id,
            'status' => 'required|in:active,deactive',
        ]);

        $refCode->code = $request->input('reference_code');
        $refCode->status = $request->input('status');
        $refCode->save();

        return redirect('reference-codes')->with('ref_code_success', 'Reference code updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $refCode = ReferenceCodes::findOrFail($id);
        $refCode->delete();

        return redirect('reference-codes')->with('ref_code_success', 'Reference code deleted.');
    }
}
