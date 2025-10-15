document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('msb-lookup-btn');
    if (!btn) return;

    const refInput = document.querySelector('input[name="ref_number"]');
    const amountInput = document.querySelector('input[name="amount"]');
    const resultDiv = document.getElementById('msb-result');

    // Format số tiền khi nhập
    amountInput.addEventListener('input', function (e) {
        let raw = e.target.value.replace(/[^\d]/g, '');
        if (raw.length > 0) {
            e.target.value = Number(raw).toLocaleString('vi-VN');
        } else {
            e.target.value = '';
        }
    });

    btn.addEventListener('click', function (event) {
        event.preventDefault();

        const refNumber = refInput ? refInput.value.trim() : '';
        const amountFormatted = amountInput ? amountInput.value.trim() : '';
        const amountRaw = amountFormatted.replace(/[^\d]/g, '');

        // Validate ref_number: tối đa 10 ký tự, chỉ chữ và số
        const refValid = /^[A-Za-z0-9]{1,10}$/.test(refNumber);
        if (!refValid) {
            resultDiv.innerHTML = "❌ Số chứng thư bảo lãnh không hợp lệ. Tối đa 10 ký tự, chỉ gồm chữ và số.";
            return;
        }

        // Validate amount: chỉ số
        if (!amountRaw || isNaN(amountRaw)) {
            resultDiv.innerHTML = "❌ Số tiền không hợp lệ. Vui lòng chỉ nhập số.";
            return;
        }

        resultDiv.innerHTML = "⏳ Đang tra cứu...";

        fetch(msb_ajax.ajax_url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                action: "msb_guarantee_lookup",
                ref_number: refNumber,
                amount: amountRaw,
            }),
        })
        .then(res => {
            console.log("Raw response:", res);
            return res.json()
        })
        .then(data => {
            console.log("Parsed JSON:", data);
            if (data.success) {
                resultDiv.innerHTML = data.data;
            } else {
                resultDiv.innerHTML = data.data || "❌ Không tìm thấy thông tin.";
            }
        })
        .catch((err) => {
            console.error(err);
            resultDiv.innerHTML = "❌ Có lỗi xảy ra khi tra cứu.";
        });
    });
});
