<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Owner</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

  <!-- Header -->
  <header class="bg-white shadow-sm sticky top-0 z-10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-xl font-bold text-gray-800">Dashboard Owner</h1>
      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">Hi, Owner 👋</span>
        <a href="{{ route('logout') }}" class="text-red-500 hover:underline text-sm">Logout</a>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="container mx-auto px-6 py-10">

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10 max-w-4xl mx-auto">
      <!-- Total Pemasukan -->
      <div class="bg-gradient-to-br from-green-100 to-green-50 p-6 rounded-2xl shadow-md border border-green-200">
        <h3 class="text-gray-600 font-medium mb-2">Total Pemasukan</h3>
        <p class="text-3xl font-bold text-green-700">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
      </div>

      <!-- Total Transaksi Lunas -->
      <div class="bg-gradient-to-br from-blue-100 to-blue-50 p-6 rounded-2xl shadow-md border border-blue-200">
        <h3 class="text-gray-600 font-medium mb-2">Total Transaksi Lunas</h3>
        <p class="text-3xl font-bold text-blue-700">{{ $totalTransaksi }}</p>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
      <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-700">📅 Pemasukan per Bulan</h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="p-4 border-b">Bulan</th>
              <th class="p-4 border-b">Total Pemasukan</th>
            </tr>
          </thead>
          <tbody class="text-gray-600">
            @forelse($pemasukanBulanan as $bulan => $total)
              <tr class="hover:bg-gray-50 transition">
                <td class="p-4 border-b">{{ $bulan }}</td>
                <td class="p-4 border-b font-medium text-gray-800">
                  Rp {{ number_format($total, 0, ',', '.') }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="2" class="p-4 text-center text-gray-500">Belum ada data pemasukan</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </main>

  <!-- Footer -->
  <footer class="mt-10 py-5 text-center text-gray-500 text-sm border-t">
    &copy; {{ date('Y') }} BookInn • Dashboard Owner
  </footer>

</body>
</html>
