document.addEventListener('DOMContentLoaded', function () {
    const checkoutPage = document.getElementById('checkoutPage');
    if (!checkoutPage) return;

    const quantityInput = document.querySelector('input[name="quantity"]');
    const eventPrice = parseInt(checkoutPage.dataset.eventPrice || '0', 10);
    const checkoutRoute = checkoutPage.dataset.checkoutRoute || '';
    const paymentStatusRoute = checkoutPage.dataset.paymentStatusRoute || '';
    const isDemo = checkoutPage.dataset.demo === '1';

    const totalPriceEl = document.getElementById('totalPriceText');
    const ticketSummaryEl = document.getElementById('ticketSummaryLine');
    const couponCodeInput = document.getElementById('couponCodeInput');
    const applyCouponButton = document.getElementById('applyCouponButton');
    const couponMessage = document.getElementById('couponMessage');
    const discountRow = document.getElementById('discountRow');
    const discountValueText = document.getElementById('discountValueText');
    const ticketPriceText = document.getElementById('ticketPriceText');
    const serviceFeeText = document.getElementById('serviceFeeText');

    let currentDiscount = 0;
    let appliedCouponCode = '';

    const formatIDR = (value) => {
        try {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
        } catch (e) {
            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    };

    const updateSummary = () => {
        const qty = Math.max(1, parseInt(quantityInput?.value || '1', 10));
        const ticketTotal = eventPrice * qty;
        const serviceFee = 5000;
        const total = Math.max(0, ticketTotal + serviceFee - currentDiscount);

        if (totalPriceEl) totalPriceEl.textContent = formatIDR(total);
        if (ticketSummaryEl) ticketSummaryEl.textContent = qty + ' x Rp ' + Number(eventPrice).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if (ticketPriceText) ticketPriceText.textContent = formatIDR(ticketTotal);
        if (serviceFeeText) serviceFeeText.textContent = formatIDR(serviceFee);
        if (discountRow) {
            if (currentDiscount > 0) {
                discountRow.classList.remove('hidden');
                discountValueText.textContent = '- ' + formatIDR(currentDiscount);
            } else {
                discountRow.classList.add('hidden');
            }
        }
    };

    if (quantityInput) {
        quantityInput.addEventListener('input', () => {
            // Revalidate coupon when quantity changes, since order amount changes.
            if (appliedCouponCode) {
                applyCoupon(appliedCouponCode, true);
            } else {
                currentDiscount = 0;
                updateSummary();
            }
        });
        updateSummary();
    }

    const applyCoupon = async (codeValue = null, isReapply = false) => {
        if (!couponCodeInput || !couponMessage || !discountRow) return;

        const code = codeValue !== null ? codeValue : couponCodeInput.value.trim();
        couponCodeInput.value = code;

        if (!code) {
            couponMessage.textContent = 'Masukkan kode promo untuk mendapatkan diskon.';
            currentDiscount = 0;
            appliedCouponCode = '';
            updateSummary();
            return;
        }

        try {
            const response = await fetch('/coupon/validate', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    order_amount: eventPrice * Math.max(1, parseInt(quantityInput?.value || '1', 10)),
                    event_id: document.getElementById('eventIdInput')?.value || null
                })
            });

            const data = await response.json();

            if (!response.ok || !data.valid || !data.coupon) {
                throw new Error(data.message || 'Kode kupon tidak valid.');
            }

            currentDiscount = parseInt(data.coupon.discount_amount || 0, 10);
            appliedCouponCode = data.coupon.code || code;
            couponMessage.textContent = 'Kupon berhasil diterapkan: ' + appliedCouponCode;
            updateSummary();
        } catch (error) {
            couponMessage.textContent = error.message;
            currentDiscount = 0;
            appliedCouponCode = '';
            updateSummary();
            if (!isReapply) {
                couponCodeInput.focus();
            }
        }
    };

    if (applyCouponButton) {
        applyCouponButton.addEventListener('click', () => applyCoupon());
    }

    const payButton = document.getElementById('payButton');
    if (payButton) {
        payButton.addEventListener('click', async function (e) {
            e.preventDefault();

            const form = document.getElementById('checkoutForm');
            const formData = new FormData(form);
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            try {
                const payload = {
                    customer_name: formData.get('customer_name'),
                    customer_email: formData.get('customer_email'),
                    customer_phone: formData.get('customer_phone'),
                    quantity: parseInt(formData.get('quantity')),
                    payment_method: formData.get('payment_method') || 'qris',
                    coupon_code: appliedCouponCode || formData.get('coupon_code') || '',
                    event_id: document.getElementById('eventIdInput')?.value || null
                };

                if (isDemo) {
                    payload.payment_status = document.querySelector('input[name="payment_status"]:checked')?.value || 'success';
                }

                const response = await fetch(checkoutRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (!response.ok) {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById('error-' + key);
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                            }
                        });
                    }
                    throw new Error(data.message || 'Checkout failed');
                }

                if (isDemo) {
                    setTimeout(() => {
                        const redirectTarget = paymentStatusRoute.replace(':id', data.order_id || data.transaction_id || '');
                        window.location.href = redirectTarget;
                    }, 800);
                } else if (window.snap) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            const redirectTarget = paymentStatusRoute.replace(':id', result.order_id || result.transaction_id || data.order_id || data.transaction_id || '');
                            window.location.href = redirectTarget;
                        },
                        onPending: function () {},
                        onError: function () {
                            alert('Payment failed. Please try again.');
                        },
                        onClose: function () {}
                    });
                } else {
                    alert('Midtrans SDK belum siap. Silakan coba lagi.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            }
        });
    }
});
