<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klinik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-slate-700">
            <h1 class="text-xl font-bold tracking-wider text-indigo-400">KLINIK APP</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-600 rounded-lg text-white font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Clinics
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Doctors
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Schedules
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Bookings
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-300 hover:bg-slate-800 hover:text-white rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Reviews
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden">
        <!-- Header -->
        <header class="h-16 flex items-center justify-between px-8 bg-white border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Bookings Management</h2>
            <div class="flex items-center gap-4">
                <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>
                <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-indigo-200">
                    A
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="flex-1 overflow-y-auto p-8">
            <div class="mb-8 flex justify-between items-end">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Daftar Bookings Terkini</h3>
                    <p class="text-sm text-gray-500 mt-1">Sesuai dengan skema database migration bookings.</p>
                </div>
                <button class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                    + Tambah Booking
                </button>
            </div>

            <!-- Stats/Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">1,204</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">43</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Confirmed</p>
                        <p class="text-2xl font-bold text-gray-900">892</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-900">21</p>
                    </div>
                </div>
            </div>

            <!-- Table Section (Booking Fields) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
                    <h4 class="font-semibold text-gray-700">Data Bookings</h4>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Cari..." class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                        <button class="px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Filter</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Pasien (User ID)</th>
                                <th class="px-6 py-4">Jadwal (Schedule ID)</th>
                                <th class="px-6 py-4">Tanggal Booking</th>
                                <th class="px-6 py-4">Waktu (Start - End)</th>
                                <th class="px-6 py-4">No. Antrian</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <!-- Dummy Data Row 1 -->
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">#BK-001</td>
                                <td class="px-6 py-4">John Doe (1)</td>
                                <td class="px-6 py-4">Dr. Smith Clinic A (3)</td>
                                <td class="px-6 py-4">2026-06-01</td>
                                <td class="px-6 py-4">08:00 - 08:30</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200">1</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Confirmed</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                                </td>
                            </tr>
                            <!-- Dummy Data Row 2 -->
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">#BK-002</td>
                                <td class="px-6 py-4">Jane Smith (2)</td>
                                <td class="px-6 py-4">Dr. Allen Clinic B (5)</td>
                                <td class="px-6 py-4">2026-06-01</td>
                                <td class="px-6 py-4">09:00 - 09:30</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200">2</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30 flex items-center justify-between">
                    <span class="text-sm text-gray-500">Menampilkan 2 dari 1,204 bookings</span>
                    <div class="flex gap-1">
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-500 hover:bg-gray-50" disabled>Sebelumnnya</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 bg-white font-medium hover:bg-gray-50">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
