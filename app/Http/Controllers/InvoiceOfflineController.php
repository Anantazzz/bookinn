<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\TipeKamar;
use Carbon\Carbon;

class InvoiceOfflineController extends Controller
{
    public function invoice($id)
{
    $reservasi = Reservasi::with(['tamuOffline', 'kamar.tipeKamar', 'pembayaran'])
        ->findOrFail($id);

    return view('resepsionis.offline.invoice', compact('reservasi'));
}
 }
