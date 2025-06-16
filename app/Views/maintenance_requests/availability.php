<?php
// File: app/views/maintenance_requests/availability.php
?>
<div class="card mt-4 p-3">
    <h5><?= __('Tenant Availability') ?></h5>
    <form method="post" id="availabilityForm">
        <input type="hidden" name="request_id" value="<?= $request_id ?>">
        <div class="mb-3">
            <label class="form-label"><?= __('Preferred Time Slot') ?></label>
            <input type="datetime-local" name="available_at" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-secondary"><?= __('Save Availability') ?></button>
    </form>
</div>

<script>
document.getElementById("availabilityForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const form = e.target;
    const payload = {
        table: "fms_request_availability",
        op: "create",
        request_id: form.request_id.value,
        available_at: form.available_at.value
    };

    const res = await fetch("/api/crud", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    });

    const result = await res.json();
    if (result.status === 200) {
        alert("✅ Availability saved!");
        location.href = "/maintenance_requests/list";
    } else {
        alert("❌ " + result.message);
    }
});
</script>
