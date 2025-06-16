<?php
// File: app/views/maintenance_requests/feedback.php
?>
<div class="card mt-4 p-3">
    <h5><?= __('Submit Feedback') ?></h5>
    <form method="post" id="feedbackForm">
        <input type="hidden" name="request_id" value="<?= $request_id ?>">
        <div class="mb-3">
            <label class="form-label"><?= __('Rating') ?> (1-5)</label>
            <input type="number" name="rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><?= __('Comments') ?></label>
            <textarea name="comments" class="form-control" rows="3"></textarea>
        </div>
        <button class="btn btn-success w-100"><?= __('Submit Feedback') ?></button>
    </form>
</div>

<script>
document.getElementById("feedbackForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const payload = {};
    formData.forEach((value, key) => payload[key] = value);
    payload.table = "fms_request_feedback";
    payload.op = "create";

    const res = await fetch("/api/crud", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    });

    const result = await res.json();
    if (result.status === 200) {
        alert("✅ Feedback submitted!");
        location.href = "/maintenance_requests/list";
    } else {
        alert("❌ " + result.message);
    }
});
</script>
