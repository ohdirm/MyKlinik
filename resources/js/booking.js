document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const subDistrictSelect = document.getElementById('sub_district');
    const villageSelect = document.getElementById('village');

    if (!provinceSelect) return;

    // Get saved values from global window object if available
    const saved = window.savedAddressData || {};
    const savedProvince = saved.province || '';
    const savedDistrict = saved.district || '';
    const savedSubDistrict = saved.subDistrict || '';
    const savedVillage = saved.village || '';

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
