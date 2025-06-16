<?php
// File: app/views/maintenance_requests/form.php
?>
<div class="card p-4 mt-4">
  <h5><?= isset($id) ? __('Edit Assignment') : __('New Assignment') ?></h5>
  <form id="assignmentForm">
    <input type="hidden" name="id" value="<?= $id ?? '' ?>">

    <div class="mb-3">
      <label class="form-label"><?=__('Request')?></label>
      <?= render_select('request_id', $requests ?? [], $row['request_id'] ?? '', 'form-select selectpicker', 'data-live-search="true"') ?>
    </div>

    <div class="mb-3">
      <label class="form-label"><?=__('Supervisor')?></label>
      <?= render_select('supervisor_id', $supervisors ?? [], $row['supervisor_id'] ?? '', 'form-select selectpicker', 'data-live-search="true"') ?>
    </div>

    <div class="mb-3">
      <label class="form-label"><?=__('Technicians')?></label>
      <?= render_multi_select('technician_ids[]', $technicians ?? [], $row['technician_ids'] ?? [], 'form-select selectpicker', 'data-live-search="true" multiple') ?>
    </div>

    <div class="mb-3">
      <label class="form-label"><?=__('Team')?></label>
      <?= render_select('team_id', $teams ?? [], $row['team_id'] ?? '', 'form-select selectpicker', 'data-live-search="true"') ?>
    </div>

    <div class="mb-3">
      <label class="form-label"><?=__('Start Time')?></label>
      <input type="datetime-local" name="started_at" class="form-control" value="<?= $row['started_at'] ?? '' ?>" />
    </div>

    <div class="mb-3">
      <label class="form-label"><?=__('Completed Time')?></label>
      <input type="datetime-local" name="completed_at" class="form-control" value="<?= $row['completed_at'] ?? '' ?>" />
    </div>

    <button type="submit" class="btn btn-primary"><?=__('Submit')?></button>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const id = "<?= $id ?? '' ?>";
  const form = document.getElementById("assignmentForm");

  if (id) {
    fetch(`/api/crud?table=fms_assignments&op=filter&p_id=${id}`).then(r => r.json()).then(d => {
      if (d.status === 200 && d.data.length === 1) {
        const r = d.data[0];
        for (const key in r) {
          const field = form.querySelector(`[name="${key}"]`);
          if (field) field.value = r[key];
        }
      }
    });
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    formData.append("table", "fms_assignments");
    formData.append("op", id ? "update" : "create");

    fetch('/api/crud', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(res => {
      if (res.status === 200) window.location.href = "/assignments/list";
      else alert(res.message);
    });
  });
});
</script>
