<?php
// File: app/views/maintenance_requests/view.php
?>
<div class="container py-4">
  <h4><?= __('Request Details') ?></h4>
  <div id="requestDetails" class="mt-3"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const id = "<?= $id ?? '' ?>";
  if (!id) return;

  fetch(`/api/crud?table=fms_maintenance_requests&op=filter&p_id=${id}`)
    .then(r => r.json())
    .then(res => {
      if (res.status === 200 && res.data.length === 1) {
        const d = res.data[0];
        const div = document.getElementById("requestDetails");
        div.innerHTML = `
          <table class="table table-bordered">
            <tr><th><?= __('Request ID') ?></th><td>${d.id}</td></tr>
            <tr><th><?= __('Tenant') ?></th><td>${d.tenant_name ?? ''}</td></tr>
            <tr><th><?= __('Property') ?></th><td>${d.property_name ?? ''}</td></tr>
            <tr><th><?= __('Priority') ?></th><td>${d.priority_name ?? ''}</td></tr>
            <tr><th><?= __('Status') ?></th><td>${d.status_name ?? ''}</td></tr>
            <tr><th><?= __('Description') ?></th><td>${d.description ?? ''}</td></tr>
            <tr><th><?= __('Photo') ?></th><td>${d.photo ? `<img src="${d.photo}" class="img-fluid" style="max-height:200px;">` : 'N/A'}</td></tr>
            <tr><th><?= __('Created At') ?></th><td>${d.created_at ?? ''}</td></tr>
            <tr><th><?= __('Updated At') ?></th><td>${d.updated_at ?? ''}</td></tr>
          </table>
        `;
      }
    });
});
</script>
