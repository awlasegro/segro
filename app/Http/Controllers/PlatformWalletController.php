<?php

namespace App\Http\Controllers;

use App\Models\OrderSetting;
use App\Models\PlatformWallet;
use Illuminate\Http\Request;

class PlatformWalletController extends Controller
{
    public function edit()
    {
        $wallet = PlatformWallet::first();
        if (!$wallet) {
            $wallet = PlatformWallet::create([
                'wallet_type' => 'USDT TRC20',
                'wallet_address' => 'TBir8eHDc8quGkkvM1mNav4deeqvJzhq',
                'qr_code' => 'images/deposit_qr.png',
            ]);
        }

        $orderSettings = OrderSetting::current();

        return view('admin.platform-wallet', compact('wallet', 'orderSettings'));
    }

    public function updateOrderSettings(Request $request)
    {
        $request->validate([
            'selected_order_commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $orderSettings = OrderSetting::current();
        $orderSettings->selected_order_commission_rate = $request->input('selected_order_commission_rate');
        $orderSettings->save();

        return redirect()->back()->with('success', 'Order settings updated successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'wallet_type' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
            'qr_code' => 'nullable|image|max:5120', // max 5MB
        ]);

        $wallet = PlatformWallet::first();
        if (!$wallet) {
            $wallet = new PlatformWallet();
        }

        $wallet->wallet_type = $request->input('wallet_type');
        $wallet->wallet_address = $request->input('wallet_address');

        if ($request->hasFile('qr_code')) {
            $file = $request->file('qr_code');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('system_wallets'), $filename);
            $wallet->qr_code = 'system_wallets/' . $filename;
        }

        $wallet->save();

        return redirect()->back()->with('success', 'Platform deposit settings updated successfully!');
    }
}
