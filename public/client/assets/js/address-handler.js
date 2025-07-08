document.addEventListener('DOMContentLoaded', function() {
    // Old values from Laravel
    const oldCity = document.getElementById('billing_city').dataset.old || '';
    const oldDistrict = document.getElementById('billing_district').dataset.old || '';
    const oldWard = document.getElementById('billing_ward').dataset.old || '';

    // Load provinces
    fetch('https://provinces.open-api.vn/api/p/')
        .then(response => response.json())
        .then(data => {
            const provinceSelect = document.getElementById('billing_city');
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name; // Lưu tên thay vì code
                option.textContent = province.name;
                option.dataset.code = province.code; // Lưu code để dùng cho API
                provinceSelect.appendChild(option);
            });

            // Restore old province selection if exists
            if (oldCity) {
                provinceSelect.value = oldCity;
                provinceSelect.dispatchEvent(new Event('change'));
            }
        })
        .catch(error => console.error('Error loading provinces:', error));

    // Province change event
    document.getElementById('billing_city').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const provinceCode = selectedOption.dataset.code;
        const districtSelect = document.getElementById('billing_district');
        const wardSelect = document.getElementById('billing_ward');
        
        // Reset districts and wards
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        
        if (provinceCode) {
            districtSelect.disabled = false;
            // Load districts
            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.districts) {
                        data.districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.name; // Lưu tên thay vì code
                            option.textContent = district.name;
                            option.dataset.code = district.code; // Lưu code để dùng cho API
                            districtSelect.appendChild(option);
                        });

                        // Restore old district selection if exists
                        if (oldDistrict) {
                            districtSelect.value = oldDistrict;
                            districtSelect.dispatchEvent(new Event('change'));
                        }
                    }
                })
                .catch(error => console.error('Error loading districts:', error));
        } else {
            districtSelect.disabled = true;
            wardSelect.disabled = true;
        }
    });

    // District change event
    document.getElementById('billing_district').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const districtCode = selectedOption.dataset.code;
        const wardSelect = document.getElementById('billing_ward');
        
        // Reset wards
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        
        if (districtCode) {
            wardSelect.disabled = false;
            // Load wards
            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.wards) {
                        data.wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.name; // Lưu tên thay vì code
                            option.textContent = ward.name;
                            wardSelect.appendChild(option);
                        });

                        // Restore old ward selection if exists
                        if (oldWard) {
                            wardSelect.value = oldWard;
                        }
                    }
                })
                .catch(error => console.error('Error loading wards:', error));
        } else {
            wardSelect.disabled = true;
        }
    });
}); 