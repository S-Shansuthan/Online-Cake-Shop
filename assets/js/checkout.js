// assets/js/checkout.js - Checkout & Payments Logic (Member 2)
document.addEventListener('DOMContentLoaded', () => {
    const quantitySelectorElement = document.getElementById('quantity');
    const summaryQty = document.getElementById('summaryQty');
    const summaryTotal = document.getElementById('summaryTotal');
    const unitPriceEl = document.getElementById('unitPrice');
    const payButton = document.getElementById('payButton');
    const checkoutForm = document.getElementById('checkoutForm');
    const errorBox = document.getElementById('errorBox');
    
    const debitCardTabButton = document.getElementById('debitCardTabButton');
    const paypalTabButton = document.getElementById('paypalTabButton');
    const codTabButton = document.getElementById('codTabButton');
    
    const debitSection = document.getElementById('debitSection');
    const paypalSection = document.getElementById('paypalSection');
    const codSection = document.getElementById('codSection');
    
    let paymentMethod = 'PayHere';
    
    if (!quantitySelectorElement) return;

    const unitPrice = parseFloat(unitPriceEl.getAttribute('data-price'));

    // Update total when quantity changes
    quantitySelectorElement.addEventListener('change', () => {
        const qty = parseInt(quantitySelectorElement.value);
        summaryQty.textContent = qty;
        
        const total = qty * unitPrice;
        summaryTotal.textContent = 'LKR ' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });

    function resetTabs() {
        debitCardTabButton.classList.remove('active');
        paypalTabButton.classList.remove('active');
        codTabButton.classList.remove('active');
        debitSection.style.display = 'none';
        paypalSection.style.display = 'none';
        codSection.style.display = 'none';
        errorBox.style.display = 'none';
    }

    // Toggle Payment Methods
    debitCardTabButton.addEventListener('click', () => {
        resetTabs();
        debitCardTabButton.classList.add('active');
        debitSection.style.display = 'block';
        paymentMethod = 'PayHere';
    });

    paypalTabButton.addEventListener('click', () => {
        if (!checkoutForm.checkValidity()) {
            errorBox.style.display = 'block';
            errorBox.textContent = 'Please fill all required delivery details before proceeding with PayPal.';
            checkoutForm.reportValidity();
            return;
        }
        resetTabs();
        paypalTabButton.classList.add('active');
        paypalSection.style.display = 'block';
        paymentMethod = 'PayPal';
    });

    codTabButton.addEventListener('click', () => {
        if (!checkoutForm.checkValidity()) {
            errorBox.style.display = 'block';
            errorBox.textContent = 'Please fill all required delivery details before proceeding with Cash on Delivery.';
            checkoutForm.reportValidity();
            return;
        }
        resetTabs();
        codTabButton.classList.add('active');
        codSection.style.display = 'block';
        paymentMethod = 'Cash on Delivery';
    });

    const processOrder = async (method) => {
        errorBox.style.display = 'none';

        const formData = {
            cake_id: document.getElementById('cakeId').value,
            full_name: document.getElementById('fullName').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            address: document.getElementById('address').value,
            custom_message: document.getElementById('customMessage').value,
            quantity: document.getElementById('quantity').value,
            payment_method: method
        };

        try {
            const response = await fetch('/Cake_Verse/api/payment/process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (result.success) {
                if (method === 'PayHere') {
                    payhere.onCompleted = function onCompleted(orderId) {
                        window.location.href = `/Cake_Verse/customer/payment-status.php?status=success&order_id=${result.order_id}`;
                    };
                    payhere.onDismissed = function onDismissed() {
                        errorBox.style.display = 'block';
                        errorBox.textContent = 'Payment cancelled by user.';
                        if (payButton) {
                            payButton.disabled = false;
                            payButton.textContent = 'Proceed to PayHere';
                        }
                    };
                    payhere.onError = function onError(error) {
                        errorBox.style.display = 'block';
                        errorBox.textContent = 'PayHere Error: ' + error;
                        if (payButton) {
                            payButton.disabled = false;
                            payButton.textContent = 'Proceed to PayHere';
                        }
                    };

                    const qty = parseInt(quantitySelectorElement.value);
                    const total = qty * unitPrice;

                    var payment = {
                        "sandbox": true,
                        "merchant_id": "1211149",
                        "return_url": "http://localhost/Cake_Verse/customer/payment-status.php",
                        "cancel_url": "http://localhost/Cake_Verse/customer/checkout.php",
                        "notify_url": "http://localhost/Cake_Verse/api/payment/process.php",
                        "order_id": result.order_id,
                        "items": "Cake Order",
                        "amount": total.toFixed(2),
                        "currency": "LKR",
                        "first_name": document.getElementById('fullName').value,
                        "last_name": "",
                        "email": document.getElementById('email').value,
                        "phone": document.getElementById('phone').value,
                        "address": document.getElementById('address').value,
                        "city": "Colombo",
                        "country": "Sri Lanka"
                    };
                    
                    
                    // MOCK PAYMENT FOR UNIVERSITY PRACTICAL TO BYPASS UNAUTHORIZED ERROR
                    payButton.textContent = 'Processing PayHere...';
                    setTimeout(() => {
                        alert('PayHere Sandbox Mock: Payment Successful!');
                        window.location.href = `/Cake_Verse/customer/payment-status.php?status=success&order_id=${result.order_id}`;
                    }, 1500);

                } else {
                    window.location.href = `/Cake_Verse/customer/payment-status.php?status=success&order_id=${result.order_id}`;
                }
            } else {
                errorBox.style.display = 'block';
                errorBox.textContent = result.message || 'Payment processing failed.';
                if (payButton) {
                    payButton.disabled = false;
                    payButton.textContent = 'Proceed to PayHere';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorBox.style.display = 'block';
            errorBox.textContent = 'A network error occurred.';
            if (payButton) {
                payButton.disabled = false;
                payButton.textContent = 'Proceed to PayHere';
            }
        }
    };

    // Handle PayHere Payment Click
    payButton.addEventListener('click', () => {
        if (!checkoutForm.checkValidity()) {
            errorBox.style.display = 'block';
            errorBox.textContent = 'Please fill all required delivery details correctly.';
            checkoutForm.reportValidity();
            return;
        }

        payButton.disabled = true;
        payButton.textContent = 'Connecting to PayHere...';
        processOrder('PayHere');
    });

    if (typeof paypal !== 'undefined') {
        paypal.Buttons({
            createOrder: function(data, actions) {
                // Convert LKR to USD roughly for PayPal demo (PayPal doesn't support LKR)
                const qty = parseInt(quantitySelectorElement.value);
                const lkrTotal = qty * unitPrice;
                const usdTotal = (lkrTotal / 300).toFixed(2); // Mock conversion rate

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: usdTotal,
                            currency_code: 'USD'
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // PayPal Transaction Successful
                    processOrder('PayPal API');
                });
            },
            onError: function(err) {
                console.error(err);
                errorBox.style.display = 'block';
                errorBox.textContent = 'PayPal transaction failed. Try again.';
            }
        }).render('#paypal-button-container');
    }
});

    const payCodButton = document.getElementById('payCodButton');
    if (payCodButton) {
        payCodButton.addEventListener('click', () => {
            if (!checkoutForm.checkValidity()) {
                errorBox.style.display = 'block';
                errorBox.textContent = 'Please fill all required delivery details correctly.';
                checkoutForm.reportValidity();
                return;
            }
            payCodButton.disabled = true;
            payCodButton.textContent = 'Confirming Order...';
            processOrder('Cash on Delivery');
        });
    }
