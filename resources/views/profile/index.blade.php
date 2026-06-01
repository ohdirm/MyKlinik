@extends('layouts.app')
@section('title', 'Profile Saya — MyKlinik911')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#f2faf5] via-white to-[#e8f5ed] dark:from-[#141b18] dark:via-[#0a0f0d] dark:to-[#141b18] py-10 transition-colors"
        x-data="profilePage()">
        <div class="max-w-2xl mx-auto px-4">
            {{-- Back --}}
            <div class="mb-6">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 group text-sm font-medium text-gray-500 dark:text-gray-400">
                    <div
                        class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-800 flex items-center justify-center group-hover:border-brand group-hover:bg-brand group-hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </div>
                    <span class="group-hover:text-brand">Kembali</span>
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('warning'))
                <div
                    class="mb-6 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 text-amber-800 dark:text-amber-300 px-5 py-4 rounded-2xl text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('success'))
                <div
                    class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-5 py-4 rounded-2xl text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center gap-2 bg-brand/10 text-brand-dark dark:bg-brand/20 dark:text-brand text-xs font-semibold px-4 py-1.5 rounded-full mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Profile Pasien
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Saya</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Kelola data diri dan anggota keluarga</p>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-2 mb-6">
                <button @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'bg-brand text-white shadow-md' : 'bg-white dark:bg-[#1c2622] text-gray-600 dark:text-gray-300 border border-[#e2efe7] dark:border-[#283731]'"
                    class="flex-1 py-3 rounded-2xl text-sm font-semibold transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Data Diri
                </button>
                <button @click="tab = 'family'"
                    :class="tab === 'family' ? 'bg-brand text-white shadow-md' : 'bg-white dark:bg-[#1c2622] text-gray-600 dark:text-gray-300 border border-[#e2efe7] dark:border-[#283731]'"
                    class="flex-1 py-3 rounded-2xl text-sm font-semibold transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    Keluarga
                </button>
            </div>

            {{-- TAB: Data Diri --}}
            <div x-show="tab === 'profile'" x-transition>
                @include('profile._form')
            </div>

            {{-- TAB: Keluarga --}}
            <div x-show="tab === 'family'" x-transition>
                @include('profile._family')
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function profilePage() {
                return {
                    tab: '{{ request('tab', 'profile') }}',
                    submitting: false,
                    showFamilyModal: false,
                    editingFamily: null,
                    familyForm: { full_name: '', relationship: '', nik: '', birth_date: '', gender: '', phone_number: '' },
                    showDeleteConfirm: false,
                    deletingId: null,

                    openAddFamily() {
                        this.editingFamily = null;
                        this.familyForm = { full_name: '', relationship: '', nik: '', birth_date: '', gender: '', phone_number: '' };
                        this.showFamilyModal = true;
                    },
                    openEditFamily(f) {
                        this.editingFamily = f;
                        this.familyForm = { full_name: f.full_name, relationship: f.relationship, nik: f.nik, birth_date: f.birth_date, gender: f.gender, phone_number: f.phone_number };
                        this.showFamilyModal = true;
                    },
                    confirmDelete(id) {
                        this.deletingId = id;
                        this.showDeleteConfirm = true;
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const provinceSelect = document.getElementById('province');
                const districtSelect = document.getElementById('district');
                const subDistrictSelect = document.getElementById('sub_district');
                const villageSelect = document.getElementById('village');

                if (!provinceSelect) return;

                const savedProvince = "{{ old('province', $profile?->province ?? '') }}";
                const savedDistrict = "{{ old('district', $profile?->district ?? '') }}";
                const savedSubDistrict = "{{ old('sub_district', $profile?->sub_district ?? '') }}";
                const savedVillage = "{{ old('village', $profile?->village ?? '') }}";

                function loadOptions(url, params, targetSelect, placeholder, savedValue, callback) {
                    const query = new URLSearchParams(params).toString();
                    targetSelect.innerHTML = `<option value="">Memuat...</option>`;
                    targetSelect.disabled = true;

                    fetch(`${url}?${query}`)
                        .then(r => r.json())
                        .then(data => {
                            targetSelect.innerHTML = `<option value="">${placeholder}</option>`;
                            let matchedId = null;
                            data.forEach(item => {
                                const opt = document.createElement('option');
                                opt.value = item.name;
                                opt.textContent = item.name;
                                opt.dataset.id = item.id;
                                if (savedValue && item.name.toLowerCase() === savedValue.toLowerCase()) {
                                    opt.selected = true;
                                    matchedId = item.id;
                                }
                                targetSelect.appendChild(opt);
                            });
                            targetSelect.disabled = false;
                            if (callback) callback(matchedId);
                        })
                        .catch(() => {
                            targetSelect.innerHTML = `<option value="">Gagal memuat data</option>`;
                        });
                }

                // Load initial provinces and trigger cascade if saved values exist
                loadOptions('/api/wilayah/provinces', {}, provinceSelect, '— Pilih Provinsi —', savedProvince, function (provId) {
                    if (provId) {
                        loadOptions('/api/wilayah/districts', { province_id: provId }, districtSelect, '— Pilih Kabupaten —', savedDistrict, function (distId) {
                            if (distId) {
                                loadOptions('/api/wilayah/subdistricts', { district_id: distId }, subDistrictSelect, '— Pilih Kecamatan —', savedSubDistrict, function (subId) {
                                    if (subId) {
                                        loadOptions('/api/wilayah/villages', { sub_district_id: subId }, villageSelect, '— Pilih Kelurahan —', savedVillage);
                                    }
                                });
                            }
                        });
                    }
                });

                provinceSelect.addEventListener('change', function () {
                    const id = this.options[this.selectedIndex]?.dataset?.id;
                    districtSelect.innerHTML = '<option value="">— Pilih Kabupaten —</option>';
                    districtSelect.disabled = true;
                    subDistrictSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
                    subDistrictSelect.disabled = true;
                    villageSelect.innerHTML = '<option value="">— Pilih Kelurahan —</option>';
                    villageSelect.disabled = true;
                    if (id) loadOptions('/api/wilayah/districts', { province_id: id }, districtSelect, '— Pilih Kabupaten —');
                });

                districtSelect.addEventListener('change', function () {
                    const id = this.options[this.selectedIndex]?.dataset?.id;
                    subDistrictSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
                    subDistrictSelect.disabled = true;
                    villageSelect.innerHTML = '<option value="">— Pilih Kelurahan —</option>';
                    villageSelect.disabled = true;
                    if (id) loadOptions('/api/wilayah/subdistricts', { district_id: id }, subDistrictSelect, '— Pilih Kecamatan —');
                });

                subDistrictSelect.addEventListener('change', function () {
                    const id = this.options[this.selectedIndex]?.dataset?.id;
                    villageSelect.innerHTML = '<option value="">— Pilih Kelurahan —</option>';
                    villageSelect.disabled = true;
                    if (id) loadOptions('/api/wilayah/villages', { sub_district_id: id }, villageSelect, '— Pilih Kelurahan —');
                });
            });
        </script>
    @endpush
@endsection