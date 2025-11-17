<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Schema;

echo "=== DEBUG KUPON SYSTEM ===\n\n";

// 1. Cek apakah kolom discount_code ada
echo "1. Checking if discount_code column exists...\n";
$hasColumn = Schema::hasColumn('pembayarans', 'discount_code');
echo "   Result: " . ($hasColumn ? "YES" : "NO") . "\n\n";

if (!$hasColumn) {
    echo "   PROBLEM: Column discount_code tidak ada!\n";
    echo "   SOLUTION: Jalankan migration: php artisan migrate\n\n";
}

// 2. Cek data pembayaran dengan discount_code
echo "2. Checking payments with discount codes...\n";
$paymentsWithDiscount = Pembayaran::whereNotNull('discount_code')->get();
echo "   Total payments with discount: " . $paymentsWithDiscount->count() . "\n";

foreach ($paymentsWithDiscount as $payment) {
    $user = $payment->reservasi->user ?? null;
    echo "   - Payment ID: {$payment->id}, User: " . ($user ? $user->email : 'N/A') . ", Code: {$payment->discount_code}\n";
}
echo "\n";

// 3. Cek user tertentu jika ada
echo "3. Checking specific user (if exists)...\n";
$testEmails = ['milea@email.com', 'test@email.com', 'user@email.com'];

foreach ($testEmails as $email) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "   User found: {$user->email} (ID: {$user->id})\n";
        
        $userPayments = Pembayaran::whereHas('reservasi', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();
        
        echo "   Total payments: " . $userPayments->count() . "\n";
        
        foreach ($userPayments as $payment) {
            echo "     - Payment ID: {$payment->id}, Discount: " . ($payment->discount_code ?? 'NULL') . "\n";
        }
        echo "\n";
    }
}

echo "=== END DEBUG ===\n";