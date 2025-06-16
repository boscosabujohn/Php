<?php
// File: app/views/assignments/list.php
?>
<div class="container py-4">
  <h4><?= __('Assignment List') ?></h4>
  <div class="table-responsive mt-3">
    <table class="table table-bordered table-striped" id="assignmentTable">
      <thead>
        <tr>
          <th><?= __('ID') ?></th>
          <th><?= __('Request') ?></th>
          <th><?= __('Supervisor') ?></th>
          <th><?= __('Technicians') ?></th>
          <th><?= __('Team') ?></th>
          <th><?= __('Started At') ?></th>
          <th><?= __('Completed At') ?></th>
          <th><?= __('Actions') ?></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  fetch(`/api/crud?table=fms_assignments&op=filter`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector("#assignmentTable tbody");
      if (data.status === 200 && Array.isArray(data.data)) {
        data.data.forEach(row => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${row.id}</td>
            <td>${row.request_code ?? row.request_id}</td>
            <td>${row.supervisor_name ?? ''}</td>
            <td>${row.technician_names ?? ''}</td>
            <td>${row.team_name ?? ''}</td>
            <td>${row.started_at ?? ''}</td>
            <td>${row.completed_at ?? ''}</td>
            <td>
              <a href="/assignments/view/${row.id}" class="btn btn-sm btn-info"><?= __('View') ?></a>
              <a href="/assignments/form/${row.id}" class="btn btn-sm btn-warning"><?= __('Edit') ?></a>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted"><?= __('No assignments found.') ?></td></tr>`;
      }
    })
    .catch(() => {
      const tbody = document.querySelector("#assignmentTable tbody");
      tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger"><?= __('Error loading data.') ?></td></tr>`;
    });
});
</script>
