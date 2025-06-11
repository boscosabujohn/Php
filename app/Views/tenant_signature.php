<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Signature - Work Completion</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .signature-pad { border: 2px dashed var(--border); border-radius: var(--radius); background: #fff; width: 100%; height: 220px; cursor: crosshair; }
        .signature-actions { margin-top: 1rem; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="card p-3 mx-auto" style="max-width:500px;">
        <h4 class="mb-3">Tenant Signature - Work Completion</h4>
        <form id="signatureForm">
            <input type="hidden" id="requestId">
            <div class="mb-2">Please sign below to confirm the work has been completed to your satisfaction.</div>
            <canvas id="signaturePad" class="signature-pad"></canvas>
            <div class="signature-actions">
                <button type="button" class="btn btn-secondary btn-sm" id="clearBtn">Clear</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit Signature</button>
            </div>
            <div id="formMessage" class="mt-2"></div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
const apiUrl = '/api/crud/';
let requestId = null;
// Get requestId from URL
let parts = window.location.pathname.split('/');
requestId = parts[parts.length-1];
$('#requestId').val(requestId);
// Signature pad logic
const canvas = document.getElementById('signaturePad');
const ctx = canvas.getContext('2d');
let drawing = false, lastX = 0, lastY = 0;
canvas.width = canvas.offsetWidth;
canvas.height = canvas.offsetHeight;
canvas.addEventListener('mousedown', e => { drawing = true; [lastX, lastY] = [e.offsetX, e.offsetY]; });
canvas.addEventListener('mousemove', e => {
    if (!drawing) return;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.strokeStyle = '#222';
    ctx.lineWidth = 2;
    ctx.stroke();
    [lastX, lastY] = [e.offsetX, e.offsetY];
});
canvas.addEventListener('mouseup', () => drawing = false);
canvas.addEventListener('mouseout', () => drawing = false);
document.getElementById('clearBtn').onclick = function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
};
$('#signatureForm').on('submit', function(e) {
    e.preventDefault();
    let dataUrl = canvas.toDataURL('image/png');
    if (dataUrl.length < 200) { $('#formMessage').text('Please provide a signature.').addClass('text-danger'); return; }
    $.post(apiUrl + 'create', { table: 'fms_request_attachments', data: { request_id: requestId, file_type: 'signature', file_data: dataUrl } }, function() {
        $('#formMessage').removeClass('text-danger').addClass('text-success').text('Signature submitted successfully.');
        setTimeout(() => { window.location.href = '/feedback/' + requestId; }, 1200);
    }).fail(function() {
        $('#formMessage').removeClass('text-success').addClass('text-danger').text('Error submitting signature.');
    });
});
</script>
</body>
</html>
