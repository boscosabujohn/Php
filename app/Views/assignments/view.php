<?php
// File: app/views/assignments/view.php
?>
<div class="container py-4">
  <h4><?= __('Assignment Details') ?></h4>
  <div id="assignmentDetails" class="mt-3"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const id = "<?= $id ?? '' ?>";
  const div = document.getElementById("assignmentDetails");

  if (!id) {
    div.innerHTML = "<p class='text-danger'><?= __('Invalid Assignment ID') ?></p>";
    return;
  }

  div.innerHTML = "<div class='text-center text-muted py-4'><?= __('Loading...') ?></div>";

  fetch(`/api/crud?table=fms_assignments&op=filter&p_id=${id}`)
    .then(r => r.json())
    .then(res => {
      if (res.status === 200 && res.data.length === 1) {
        const d = res.data[0];
        div.innerHTML = `
          <table class="table table-bordered">
            <tr><th><?= __('Assignment ID') ?></th><td>${d.id}</td></tr>
            <tr><th><?= __('Request') ?></th><td>${d.request_id ?? ''}</td></tr>
            <tr><th><?= __('Supervisor') ?></th><td>${d.supervisor_name ?? ''}</td></tr>
            <tr><th><?= __('Technicians') ?></th><td>${d.technician_names ?? ''}</td></tr>
            <tr><th><?= __('Team') ?></th><td>${d.team_name ?? ''}</td></tr>
            <tr><th><?= __('Started At') ?></th><td>${d.started_at ?? ''}</td></tr>
            <tr><th><?= __('Completed At') ?></th><td>${d.completed_at ?? ''}</td></tr>
            <tr><th><?= __('Created At') ?></th><td>${d.created_at ?? ''}</td></tr>
          </table>
        `;
      } else {
        div.innerHTML = "<p class='text-danger'><?= __('Assignment not found.') ?></p>";
      }
    })
    .catch(err => {
      console.error(err);
      div.innerHTML = "<p class='text-danger'><?= __('Failed to load assignment details.') ?></p>";
    });
});
</script>
