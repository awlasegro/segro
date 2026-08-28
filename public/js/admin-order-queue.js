/* admin-order-queue.js - Admin order queue inline price/commission editing */
(function () {
    var csrfToken = document.querySelector('input[name="_token"]').value;

    document.querySelectorAll('tr[data-order-id]').forEach(function (row) {
        var priceInput = row.querySelector('.edit-price');
        var commissionInput = row.querySelector('.edit-commission');

        if (!priceInput || !commissionInput) {
            return; // completed row, nothing editable
        }

        var totalCell = row.querySelector('.total-value');
        var statusLabel = row.querySelector('.save-status');
        var orderId = row.getAttribute('data-order-id');
        var savedPrice = priceInput.value;
        var savedCommission = commissionInput.value;

        function recalcTotal() {
            var price = parseFloat(priceInput.value);
            var commission = parseFloat(commissionInput.value);

            if (!isNaN(price) && !isNaN(commission)) {
                totalCell.textContent = (price + commission).toFixed(2);
            }
        }

        function saveRow() {
            var price = parseFloat(priceInput.value);
            var commission = parseFloat(commissionInput.value);

            if (isNaN(price) || price < 0 || isNaN(commission) || commission < 0) {
                statusLabel.textContent = 'Invalid value';
                statusLabel.style.color = '#dc3545';
                return;
            }

            // Nothing changed since the last successful save, skip the request.
            if (priceInput.value === savedPrice && commissionInput.value === savedCommission) {
                return;
            }

            recalcTotal();
            statusLabel.textContent = 'Saving...';
            statusLabel.style.color = '#6c757d';

            fetch('/order-queue/' + orderId + '/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ price: price, commission: commission }),
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    });
                })
                .then(function (result) {
                    if (!result.ok) {
                        statusLabel.textContent = result.data.error || 'Save failed';
                        statusLabel.style.color = '#dc3545';
                        return;
                    }

                    priceInput.value = result.data.price;
                    commissionInput.value = result.data.commission;
                    savedPrice = priceInput.value;
                    savedCommission = commissionInput.value;
                    totalCell.textContent = result.data.total_amount;
                    statusLabel.textContent = 'Saved';
                    statusLabel.style.color = '#28a745';
                })
                .catch(function () {
                    statusLabel.textContent = 'Save failed';
                    statusLabel.style.color = '#dc3545';
                });
        }

        // Live total as the admin types, actual save once they leave the field.
        priceInput.addEventListener('input', recalcTotal);
        commissionInput.addEventListener('input', recalcTotal);
        priceInput.addEventListener('blur', saveRow);
        commissionInput.addEventListener('blur', saveRow);
    });
})();
