/**
 * MyKlinik911 — Booking Page JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    const selectDokter = document.getElementById('select-dokter');
    const selectJadwal = document.getElementById('select-jadwal');
    const examDate = document.getElementById('exam-date');
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const subDistrictSelect = document.getElementById('sub_district');
    const villageSelect = document.getElementById('village');

    // === Live clock ===
    function updateClock() {
        const now = new Date();
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const day = days[now.getDay()];
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const date = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const el = document.getElementById('live-clock');
        if (el) el.textContent = `${day}, ${date} — ${time} WIB`;
    }
    updateClock();
    setInterval(updateClock, 1000);

    // === Birth date combiner ===
    const birthDay = document.getElementById('birth-day');
    const birthMonth = document.getElementById('birth-month');
    const birthYear = document.getElementById('birth-year');
    const birthHidden = document.getElementById('birth-date-hidden');

    function combineBirthDate() {
        const d = birthDay?.value?.padStart(2, '0');
        const m = birthMonth?.value?.padStart(2, '0');
        const y = birthYear?.value;
        if (d && m && y && y.length === 4) {
            birthHidden.value = `${y}-${m}-${d}`;
        }
    }
    [birthDay, birthMonth, birthYear].forEach(el => {
        if (el) el.addEventListener('input', combineBirthDate);
    });

    // === Doctor + Date -> Schedule cascading ===
    function loadSchedules() {
        const doctorId = selectDokter?.value;
        const date = examDate?.value;
        if (!doctorId || !date) {
            if (selectJadwal) { selectJadwal.disabled = true; selectJadwal.innerHTML = '<option value="">— Pilih jadwal —</option>'; }
            return;
        }

        // Determine the day of week from the selected date
        const selectedDate = new Date(date);
        const dayOfWeek = selectedDate.getDay(); // 0=Sunday, 6=Saturday

        fetch(`/api/schedules/${doctorId}`)
            .then(r => r.json())
            .then(schedules => {
                selectJadwal.innerHTML = '<option value="">— Pilih jadwal —</option>';
                const filtered = schedules.filter(s => s.day_of_week === dayOfWeek);
                if (filtered.length === 0) {
                    selectJadwal.innerHTML = '<option value="">Tidak ada jadwal pada hari ini</option>';
                    selectJadwal.disabled = true;
                    return;
                }
                filtered.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = `${s.day_name} — ${s.start_time} - ${s.end_time} (Maks: ${s.max_patients} pasien)`;
                    selectJadwal.appendChild(opt);
                });
                selectJadwal.disabled = false;
            })
            .catch(() => { selectJadwal.innerHTML = '<option value="">Gagal memuat jadwal</option>'; });
    }

    if (selectDokter) selectDokter.addEventListener('change', loadSchedules);
    if (examDate) examDate.addEventListener('change', loadSchedules);

    // === Wilayah cascading ===
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
    if (provinceSelect) {
        loadOptions('/api/wilayah/provinces', {}, provinceSelect, '— Pilih Provinsi —');

        provinceSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const id = selected?.dataset?.id;
            districtSelect.innerHTML = '<option value="">— Pilih Kabupaten —</option>';
            districtSelect.disabled = true;
            subDistrictSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
            subDistrictSelect.disabled = true;
            villageSelect.innerHTML = '<option value="">— Pilih Kelurahan —</option>';
            villageSelect.disabled = true;
            if (id) loadOptions('/api/wilayah/districts', { province_id: id }, districtSelect, '— Pilih Kabupaten —');
        });

        districtSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const id = selected?.dataset?.id;
            subDistrictSelect.innerHTML = '<option value="">— Pilih Kecamatan —</option>';
            subDistrictSelect.disabled = true;
            villageSelect.innerHTML = '<option value="">— Pilih Kelurahan —</option>';
            villageSelect.disabled = true;
            if (id) loadOptions('/api/wilayah/subdistricts', { district_id: id }, subDistrictSelect, '— Pilih Kecamatan —');
        });

        subDistrictSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const id = selected?.dataset?.id;
            villageSelect.innerHTML = '<option value="">— Pilih Kelurahan —</option>';
            villageSelect.disabled = true;
            if (id) loadOptions('/api/wilayah/villages', { sub_district_id: id }, villageSelect, '— Pilih Kelurahan —');
        });
    }
});
