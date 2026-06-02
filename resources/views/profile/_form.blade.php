{{-- Profile Form Partial --}}
<div class="bg-white dark:bg-[#1c2622] rounded-3xl shadow-xl overflow-hidden border border-[#e2efe7] dark:border-[#283731]">
    {{-- Photo Section --}}
    <div class="bg-gradient-to-r from-brand to-teal-600 px-6 py-8 text-center">
        <div class="relative inline-block">
            <div class="w-24 h-24 rounded-full border-4 border-white/30 overflow-hidden mx-auto bg-white/20 flex items-center justify-center">
                @if($profile?->profile_photo)
                    <img src="{{ asset('storage/' . $profile->profile_photo) }}" class="w-full h-full object-cover" alt="Profile">
                @else
                    <span class="text-3xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                @endif
            </div>
            <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data" id="photo-form">
                @csrf
                <label class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 text-brand-dark" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>
                    <input type="file" name="profile_photo" class="hidden" accept="image/*" onchange="document.getElementById('photo-form').submit()">
                </label>
            </form>
        </div>
        <h2 class="text-white font-bold text-lg mt-3">{{ Auth::user()->name }}</h2>
        <p class="text-white/70 text-sm">{{ Auth::user()->email }}</p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-5" @submit="submitting = true" onsubmit="return submitProfile(this)">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- NIK --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">NIK <span class="text-red-500">*</span></label>
            <input type="text" name="nik" class="input-base" maxlength="16" placeholder="Masukkan 16 digit NIK" value="{{ old('nik', $profile?->nik ?? '') }}" required>
        </div>

        {{-- Nama --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" class="input-base" placeholder="Nama lengkap sesuai KTP" value="{{ old('full_name', $profile?->full_name ?? Auth::user()->name) }}" required>
        </div>

        {{-- Tanggal Lahir --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="birth_date" class="input-base" value="{{ old('birth_date', $profile?->birth_date?->format('Y-m-d') ?? '') }}" required>
        </div>

        {{-- Jenis Kelamin --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-3" x-data="{ gender: '{{ old('gender', $profile?->gender ?? '') }}' }">
                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white dark:bg-[#141b18]"
                       :class="gender === 'L' ? 'border-brand bg-brand/5 dark:bg-brand/10' : 'border-[#e2efe7] dark:border-[#283731]'">
                    <input type="radio" name="gender" value="L" class="sr-only" x-model="gender">
                    <span class="text-xl">👨</span><span class="font-medium text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                </label>
                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white dark:bg-[#141b18]"
                       :class="gender === 'P' ? 'border-brand bg-brand/5 dark:bg-brand/10' : 'border-[#e2efe7] dark:border-[#283731]'">
                    <input type="radio" name="gender" value="P" class="sr-only" x-model="gender">
                    <span class="text-xl">👩</span><span class="font-medium text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                </label>
            </div>
        </div>

        {{-- No HP --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No HP/WhatsApp <span class="text-red-500">*</span></label>
            @php
                $phone = old('phone_number', $profile?->phone_number ?? '');
                // If it starts with +62, keep it. If it starts with 62, prepend +. If it starts with 08, strip 0.
                if (str_starts_with($phone, '62')) $phone = '+' . $phone;
                if (str_starts_with($phone, '0')) $phone = substr($phone, 1);
                // intlTelInput will handle the rest
            @endphp
            <input type="tel" name="phone_number" id="profile-phone" class="input-base w-full" placeholder="81234567890" value="{{ $phone }}" required>
        </div>

        {{-- Email (readonly) --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" class="input-base bg-gray-50 dark:bg-[#0f1714] opacity-60 cursor-not-allowed" value="{{ Auth::user()->email }}" disabled>
            <p class="text-xs text-gray-400 mt-1">Email dari akun Anda, tidak dapat diubah di sini.</p>
        </div>

        {{-- Alamat Lengkap --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
            <textarea name="address" rows="3" class="input-base" placeholder="Nama jalan, nomor rumah, RT/RW" required>{{ old('address', $profile?->address ?? '') }}</textarea>
        </div>

        {{-- Provinsi --}}
        <div>
            <label for="province" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Provinsi <span class="text-red-500">*</span></label>
            <select name="province" id="province" class="input-base" required>
                <option value="">— Pilih Provinsi —</option>
            </select>
        </div>

        {{-- Kabupaten/Kota --}}
        <div>
            <label for="district" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
            <select name="district" id="district" class="input-base" disabled required>
                <option value="">— Pilih Kabupaten —</option>
            </select>
        </div>

        {{-- Kecamatan --}}
        <div>
            <label for="sub_district" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kecamatan <span class="text-red-500">*</span></label>
            <select name="sub_district" id="sub_district" class="input-base" disabled required>
                <option value="">— Pilih Kecamatan —</option>
            </select>
        </div>

        {{-- Kelurahan/Desa --}}
        <div>
            <label for="village" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
            <select name="village" id="village" class="input-base" disabled required>
                <option value="">— Pilih Kelurahan —</option>
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit" :disabled="submitting" class="btn-primary w-full py-3.5 !bg-brand hover:!bg-brand-dark transition shadow-lg active:scale-95 disabled:opacity-50">
            <span x-show="!submitting">Simpan Profile</span>
            <span x-show="submitting" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                Menyimpan...
            </span>
        </button>
    </form>
</div>
