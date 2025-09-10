<?php

namespace App\Http\Controllers\Backend;

use App\Models\Transaksi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Config;

class TransaksiController extends Controller
{

    public function index()
    {
        $user = Auth::guard('admin')->user();
        $role = $user->getRoleNames()->first();

        if ($role === 'Masseur') {
            $transaksis = Transaksi::with(['masseur', 'customer'])
                ->where('masseur_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($role === 'Customer') {
            $transaksis = Transaksi::with(['masseur', 'customer'])
                ->where('user_order_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $transaksis = Transaksi::with(['masseur', 'customer'])->orderBy('created_at', 'desc')->get();
        }

        return view('backend.pages.transaksi.index', compact('transaksis'));
    }

    public function getSnapToken(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'INV-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => Auth::guard('admin')->user()->name ?? 'Guest',
                'email' => Auth::guard('admin')->user()->email ?? 'guest@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['snap_token' => $snapToken]);
    }

    public function store(Request $request)
    {
        Log::info('Request store transaksi:', $request->all());

        $transaksi = Transaksi::create([
            'masseur_id' => $request->masseur_id,
            'user_order_id' => Auth::guard('admin')->user()->id,
            'tanggal_waktu' => $request->tanggal_waktu,
            'invoice_id' => $request->invoice_id,
            'total' => $request->total,
            'alamat' => $request->alamat,
        ]);

        return response()->json(['success' => true]);
    }
}
