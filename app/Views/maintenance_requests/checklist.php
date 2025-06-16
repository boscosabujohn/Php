<?php
// File: app/views/maintenance_requests/checklist.php
?>
<div class="card mt-4 p-3">
    <h5><?= __('Checklist') ?></h5>
    <form id="checklistForm">
        <input type="hidden" name="assignment_id" value="<?= $assignment_id ?>">
        <ul class="list-group">
            <?php foreach($checklist as $item): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" class="form-check-input me-2 item-check" name="items[]" value="<?= $item['id'] ?>" <?= $item['is_completed'] ? 'checked' : '' ?>>
                        <?= esc($item['item_text']) ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="submit" class="btn btn-primary mt-3"><?= __('Save') ?></button>
    </form>
</div>

<script>
document.getElementById("checklistForm").addEventListener("submit", async function (e) {
    e.preventDefault();
    const checkedItems = Array.from(document.querySelectorAll(".item-check"))
        .filter(cb => cb.checked)
        .map(cb => cb.value);

    const payload = {
        assignment_id: document.querySelector('input[name="assignment_id"]').value,
        completed_items: checkedItems
    };

    const res = await fetch('/api/complete_checklist_items', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });

    const result = await res.json();
    if (result.status === 200) {
        alert('✅ Checklist updated successfully!');
        location.reload();
    } else {
        alert(result.message || 'Error updating checklist.');
    }
});
</script>