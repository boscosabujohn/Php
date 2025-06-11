<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Feedback</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .star-rating { font-size: 2rem; color: #ffd700; cursor: pointer; }
        .star-rating .star { transition: color 0.2s; }
        .star-rating .star.inactive { color: #ccc; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="card p-3 mx-auto" style="max-width:500px;">
        <h4 class="mb-3">Technician Feedback</h4>
        <form id="techFeedbackForm">
            <input type="hidden" id="requestId">
            <div class="mb-2">Please rate the assignment and provide your comments.</div>
            <div class="mb-2 star-rating" id="starRating">
                <span class="star inactive" data-value="1">&#9733;</span>
                <span class="star inactive" data-value="2">&#9733;</span>
                <span class="star inactive" data-value="3">&#9733;</span>
                <span class="star inactive" data-value="4">&#9733;</span>
                <span class="star inactive" data-value="5">&#9733;</span>
            </div>
            <input type="hidden" id="rating" value="0">
            <div class="mb-2">
                <textarea id="comments" class="form-control" rows="3" placeholder="Your comments..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Submit Feedback</button>
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
$('#requestId').val(requestId);
$('#starRating .star').on('mouseenter', function() {
    let val = $(this).data('value');
    $('#starRating .star').each(function(i, el) {
        $(el).toggleClass('inactive', i+1 > val);
    });
});
$('#starRating .star').on('mouseleave', function() {
    let val = $('#rating').val();
    $('#starRating .star').each(function(i, el) {
        $(el).toggleClass('inactive', i+1 > val);
    });
});
$('#starRating .star').on('click', function() {
    let val = $(this).data('value');
    $('#rating').val(val);
    $('#starRating .star').each(function(i, el) {
        $(el).toggleClass('inactive', i+1 > val);
    });
});
$('#techFeedbackForm').on('submit', function(e) {
    e.preventDefault();
    let rating = $('#rating').val();
    let comments = $('#comments').val();
    if (rating < 1) { $('#formMessage').text('Please select a rating.').addClass('text-danger'); return; }
    $.post(apiUrl + 'create', { table: 'fms_request_feedback', data: { request_id: requestId, rating, comments, feedback_by: 'technician' } }, function() {
        $('#formMessage').removeClass('text-danger').addClass('text-success').text('Thank you for your feedback!');
        setTimeout(() => { window.location.href = '/technicianDashboard'; }, 1200);
    }).fail(function() {
        $('#formMessage').removeClass('text-success').addClass('text-danger').text('Error submitting feedback.');
    });
});
</script>
</body>
</html>
