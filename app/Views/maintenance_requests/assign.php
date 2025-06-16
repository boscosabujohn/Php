<?php
// File: app/Views/maintenance_requests/assign.php
?>
<div class="card mt-4 p-3">
    <h5><?= __('Assign Technicians') ?></h5>
    <form method="post" id="assignForm">
        <input type="hidden" name="request_id" value="<?= $request_id ?? '' ?>">

        <div class="mb-3">
            <label class="form-label"><?= __('Supervisor') ?></label>
            <?= render_select('supervisor_id', $supervisors ?? [], '', 'form-select selectpicker', 'data-live-search="true"') ?>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= __('Technicians') ?></label>
            <?= render_multi_select('technician_ids[]', $technicians ?? [], '', 'form-select selectpicker', 'data-live-search="true" multiple') ?>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= __('Planner Comments') ?></label>
            <textarea name="planner_comments" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><?= __('Assign') ?></button>
    </form>
</div>

<script>
document.getElementById('assignForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const payload = {};
    formData.forEach((v, k) => {
        if (k.endsWith('[]')) {
            const key = k.replace('[]', '');
            if (!payload[key]) payload[key] = [];
            payload[key].push(v);
        } else {
            payload[k] = v;
        }
    });

    const res = await fetch('/api/assign_technicians', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.status === 200) {
        alert('✅ Assigned Successfully!');
        location.reload();
    } else {
        alert(result.message || 'Something went wrong!');
    }
});
</script>
