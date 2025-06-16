<?php
// File: app/views/maintenance_requests/attachments.php
?>
<div class="card mt-4 p-3">
    <h5><?= __('Upload Attachments') ?></h5>
    <form method="post" enctype="multipart/form-data" id="attachmentForm">
        <input type="hidden" name="request_id" value="<?= $request_id ?>">
        <div class="mb-3">
            <label class="form-label"><?= __('Select File') ?></label>
            <input type="file" name="file" class="form-control" required accept="image/*,.pdf">
        </div>
        <button type="submit" class="btn btn-secondary"><?= __('Upload') ?></button>
    </form>
</div>

<script>
document.getElementById("attachmentForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    fd.append("table", "fms_request_attachments");
    fd.append("op", "create");

    const res = await fetch("/api/files/upload", {
        method: "POST",
        body: fd
    });

    const result = await res.json();
    if (result.status === 200) {
        alert("✅ Upload successful!");
        form.reset();
    } else {
        alert("❌ Upload failed: " + result.message);
    }
});
</script>