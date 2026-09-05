/* admin-reference-codes.js - Generate Reference Code button + modal */

function generateReferenceCode() {
    var modal = document.getElementById('generated-code-modal');
    var generateUrl = modal.dataset.generateUrl;
    var csrfToken = modal.dataset.csrfToken;

    fetch(generateUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Request failed');
            }
            return response.json();
        })
        .then(function (data) {
            document.getElementById('generated-code-value').value = data.code;
            document.getElementById('generated-code-url').value =
                window.location.origin + '/user-register?refrence-code=' + encodeURIComponent(data.code);
            document.getElementById('generated-code-copied-msg').style.display = 'none';
            document.getElementById('generated-url-copied-msg').style.display = 'none';
            $('#generated-code-modal').modal('show');
        })
        .catch(function () {
            alert('Failed to generate a reference code. Please try again.');
        });
}

function copyGeneratedValue(inputId, msgId) {
    var input = document.getElementById(inputId);
    input.select();
    input.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(input.value).then(function () {
        document.getElementById(msgId).style.display = 'block';
    }).catch(function () {
        document.execCommand('copy');
        document.getElementById(msgId).style.display = 'block';
    });
}

// Refresh the list once the modal is dismissed so the new code shows up in the table.
$('#generated-code-modal').on('hidden.bs.modal', function () {
    location.reload();
});
