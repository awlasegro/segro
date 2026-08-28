<?php

namespace App\Http\Controllers;

use App\Models\OrderList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OrderListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderList = OrderList::all();

        return view('admin.ordersList', compact('orderList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-order-list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Create a new instance of OrderList
        $order = new OrderList();
        $order->title = $validatedData['title'];
        $order->price = $validatedData['price'];
        $order->description = $validatedData['description'] ?? null;
        $order->status = 'Active';

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('OrderImages'), $imageName);
            $order->image = $imageName;
        }

        // Save the data to the database
        $order->save();

        // Redirect or return response
        return redirect('order-list')->with('order_success', 'Order created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = OrderList::findOrFail($id);
        return view('admin.edit-order-list', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $order = OrderList::findOrFail($id);
        $order->title = $validatedData['title'];
        $order->price = $validatedData['price'];
        $order->description = $validatedData['description'] ?? null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($order->image && File::exists(public_path('OrderImages/' . $order->image))) {
                File::delete(public_path('OrderImages/' . $order->image));
            }

            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('OrderImages'), $imageName);
            $order->image = $imageName;
        }

        $order->save();

        return redirect('order-list')->with('order_success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = OrderList::findOrFail($id);

        // Delete the image file if it exists
        if ($order->image && File::exists(public_path('OrderImages/' . $order->image))) {
            File::delete(public_path('OrderImages/' . $order->image));
        }

        $order->delete();

        return redirect('order-list')->with('order_success', 'Order deleted successfully.');
    }
}
