<?php
// File: app/views/maintenance_requests/list.php
?>
<div class="container py-4">
  <h4><?= __('Maintenance Requests') ?></h4>
  <div class="table-responsive mt-3">
    <table class="table table-bordered table-striped" id="requestTable">
      <thead>
        <tr>
          <th><?= __('ID') ?></th>
          <th><?= __('Tenant') ?></th>
          <th><?= __('Property') ?></th>
          <th><?= __('Priority') ?></th>
          <th><?= __('Status') ?></th>
          <th><?= __('Created At') ?></th>
          <th><?= __('Actions') ?></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  fetch(`/api/crud?table=fms_maintenance_requests&op=filter`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector("#requestTable tbody");
      if (data.status === 200) {
        data.data.forEach(row => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${row.id}</td>
            <td>${row.tenant_name ?? ''}</td>
            <td>${row.property_name ?? ''}</td>
            <td>${row.priority_name ?? ''}</td>
            <td>${row.status_name ?? ''}</td>
            <td>${row.created_at ?? ''}</td>
            <td>
              <a href="/maintenance_requests/view/${row.id}" class="btn btn-sm btn-info">View</a>
              <a href="/maintenance_requests/form/${row.id}" class="btn btn-sm btn-warning">Edit</a>
            </td>
          `;
          tbody.appendChild(tr);
        });
      }
    });
});
</script>
