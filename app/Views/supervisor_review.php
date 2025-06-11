<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor/Admin Review</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .review-section { margin-top: 2rem; max-width: 700px; margin-left: auto; margin-right: auto; }
        .star-rating { font-size: 1.5rem; color: #ffd700; }
        .star-rating .star.inactive { color: #ccc; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="card p-3 review-section">
        <h4 class="mb-3">Supervisor/Admin Review</h4>
        <div id="reviewDetails"></div>
        <form id="reviewForm" class="mt-3">
            <div class="mb-2">Add your review or comments:</div>
            <textarea id="reviewComments" class="form-control" rows="3" placeholder="Supervisor/Admin comments..."></textarea>
            <button type="submit" class="btn btn-primary btn-sm mt-2">Submit Review</button>
            <div id="formMessage" class="mt-2"></div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
const apiUrl = '/api/crud/';
let requestId = null;
let parts = window.location.pathname.split('/');
requestId = parts[parts.length-1];
function loadFeedbackDetails() {
    $.post(apiUrl + 'filter', { table: 'fms_request_feedback', filters: { request_id: requestId } }, function(res) {
        let html = '';
        (res || []).forEach(function(fb) {
            html += `<div class='mb-2'><b>${fb.feedback_by ? fb.feedback_by.charAt(0).toUpperCase() + fb.feedback_by.slice(1) : 'User'}:</b> ` +
                `<span class='star-rating'>` +
                Array.from({length:5}, (_,i) => `<span class='star${i+1>fb.rating?' inactive':''}'>&#9733;</span>`).join('') +
                `</span><br><span class='text-muted small'>${fb.comments||''}</span></div>`;
        });
        $('#reviewDetails').html(html || '<div class="text-muted">No feedback yet.</div>');
    });
}
$('#reviewForm').on('submit', function(e) {
    e.preventDefault();
    let comments = $('#reviewComments').val();
    $.post(apiUrl + 'create', { table: 'fms_request_feedback', data: { request_id: requestId, comments, feedback_by: 'supervisor' } }, function() {
        $('#formMessage').removeClass('text-danger').addClass('text-success').text('Review submitted.');
        loadFeedbackDetails();
        $('#reviewComments').val('');
    }).fail(function() {
        $('#formMessage').removeClass('text-success').addClass('text-danger').text('Error submitting review.');
    });
});
$(function() { loadFeedbackDetails(); });
</script>
</body>
</html>
