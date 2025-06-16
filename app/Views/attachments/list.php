

<?php
// File: app/views/attachments/list.php
?>
<div class="container py-4">
  <h4><?= __('Request Attachments') ?></h4>
  <div class="table-responsive mt-3">
    <table class="table table-bordered table-striped" id="attachmentTable">
      <thead>
        <tr>
          <th><?= __('ID') ?></th>
          <th><?= __('Request ID') ?></th>
          <th><?= __('File') ?></th>
          <th><?= __('Uploaded By') ?></th>
          <th><?= __('Uploaded At') ?></th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  fetch("/api/crud?table=fms_request_attachments&op=filter")
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector("#attachmentTable tbody");
      if (data.status === 200 && Array.isArray(data.data)) {
        data.data.forEach(row => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${row.id}</td>
            <td>${row.request_id}</td>
            <td>${row.file_path ? `<a href="${row.file_path}" target="_blank">View File</a>` : ''}</td>
            <td>${row.uploaded_by_name ?? row.uploaded_by}</td>
            <td>${row.uploaded_at ?? ''}</td>
          `;
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted"><?= __('No records found.') ?></td></tr>`;
      }
    })
    .catch(() => {
      const tbody = document.querySelector("#attachmentTable tbody");
      tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger"><?= __('Failed to load data.') ?></td></tr>`;
    });
});
</script>