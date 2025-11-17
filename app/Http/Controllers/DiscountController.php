<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // DAFTAR KODE DISKON (HARDCODE - BISA DIUBAH ADMIN)
    private static $discountCodes = [
        'WELCOME10' => [
            'name' => 'Welcome Discount',
            'type' => 'percentage', // percentage atau fixed
            'value' => 10, // 10% atau Rp 10.000
            'min_amount' => 500000, // Minimal transaksi Rp 500.000
            'active' => true,
            'description' => 'Diskon 10% untuk customer baru'
        ],
        'WEEKEND20' => [
            'name' => 'Weekend Special',
            'type' => 'percentage',
            'value' => 20,
            'min_amount' => 1000000, // Minimal transaksi Rp 1.000.000
            'active' => true,
            'description' => 'Diskon 20% untuk booking weekend'
        ],
        'SAVE50K' => [
            'name' => 'Fixed Discount',
            'type' => 'fixed',
            'value' => 50000, // Rp 50.000
            'min_amount' => 300000, // Minimal transaksi Rp 300.000
            'active' => true,
            'description' => 'Potongan langsung Rp 50.000'
        ],
    ];

    // VALIDASI KODE DISKON
    public static function validateDiscount($code, $totalAmount, $userId = null)
    {
        $code = strtoupper(trim($code)); // Normalize kode
        
        // Cek apakah kode ada dan aktif
        if (!isset(self::$discountCodes[$code]) || !self::$discountCodes[$code]['active']) {
            return [
                'valid' => false,
                'message' => 'Kode diskon tidak valid atau sudah tidak aktif.'
            ];
        }
        
        // Cek apakah user sudah pernah menggunakan kupon ini
        if ($userId) {
            try {
                // Cek apakah kolom discount_code ada
                if (\Illuminate\Support\Facades\Schema::hasColumn('pembayarans', 'discount_code')) {
                    $hasUsed = \App\Models\Pembayaran::whereHas('reservasi', function($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })->where('discount_code', $code)->exists();
                    
                    if ($hasUsed) {
                        return [
                            'valid' => false,
                            'message' => 'Anda sudah pernah menggunakan kupon ini sebelumnya.'
                        ];
                    }
                }
                // Jika kolom belum ada, skip validasi (semua user bisa pakai)
            } catch (\Exception $e) {
                // Jika ada error, skip validasi
                logger()->warning('Error checking discount usage: ' . $e->getMessage());
            }
        }
        
        $discount = self::$discountCodes[$code];
        
        // Cek minimal amount
        if ($totalAmount < $discount['min_amount']) {
            return [
                'valid' => false,
                'message' => 'Minimal transaksi Rp ' . number_format($discount['min_amount'], 0, ',', '.') . ' untuk menggunakan kode ini.'
            ];
        }
        
        // Hitung diskon
        $discountAmount = self::calculateDiscount($discount, $totalAmount);
        
        return [
            'valid' => true,
            'code' => $code,
            'name' => $discount['name'],
            'description' => $discount['description'],
            'discount_amount' => $discountAmount,
            'final_amount' => $totalAmount - $discountAmount,
            'message' => 'Kode diskon berhasil diterapkan!'
        ];
    }
    
    // HITUNG JUMLAH DISKON
    private static function calculateDiscount($discount, $totalAmount)
    {
        if ($discount['type'] === 'percentage') {
            return ($totalAmount * $discount['value']) / 100;
        } else {
            return min($discount['value'], $totalAmount); // Tidak boleh lebih dari total
        }
    }
    
    // API ENDPOINT UNTUK AJAX VALIDATION
    public function checkDiscount(Request $request)
    {
        $code = $request->input('code');
        $totalAmount = (float) $request->input('total_amount');
        $userId = auth()->id(); // Ambil ID user yang sedang login
        
        $result = self::validateDiscount($code, $totalAmount, $userId);
        
        return response()->json($result);
    }
    
    // GET SEMUA KODE DISKON AKTIF (UNTUK ADMIN)
    public static function getActiveCodes()
    {
        return array_filter(self::$discountCodes, function($discount) {
            return $discount['active'];
        });
    }
}