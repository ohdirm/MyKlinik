/**
 * MyKlinik911 — Booking Page JavaScript
 * Handles: Wilayah cascading (Province → District → Sub-district → Village)
 */
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const subDistrictSelect = document.getElementById('sub_district');
    const villageSelect = document.getElementById('village');

    if (!provinceSelect) return;

    function loadOptions(url, params, targetSelect, placeholder) {
        const query = new URLSearchParams(params).toString();
        targetSelect.innerHTML = `<option value="">Memuat...</option>`;
        targetSelect.disabled = true;

        fetch(`${url}?${query}`)
            .then(r => r.json())
            .then(data => {
                targetSelect.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.name;
                    opt.textContent = item.name;
                    opt.dataset.id = item.id;
                    targetSelect.appendChild(opt);
                });
                targetSelect.disabled = false;
            })
            .catch(() => {
                targetSelect.innerHTML = `<option value="">Gagal memuat data</option>`;
            });
    }

    // Load provinces on page load
    loadOptions('/api/wilayah/provinces', {}, provinceSelect, '— Pilih Provinsi —');

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
