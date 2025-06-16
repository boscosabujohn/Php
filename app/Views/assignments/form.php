<script>
document.addEventListener("DOMContentLoaded", () => {
  const id = "<?= $id ?? '' ?>";
  const div = document.getElementById("requestDetails");

  if (!id) {
    div.innerHTML = "<p class='text-danger'><?= __('Invalid Request ID') ?></p>";
    return;
  }

  div.innerHTML = "<div class='text-center text-muted py-4'><?= __('Loading...') ?></div>";

  fetch(`/api/crud?table=fms_maintenance_requests&op=filter&p_id=${id}`)
    .then(r => r.json())
    .then(res => {
      if (res.status === 200 && res.data.length === 1) {
        const d = res.data[0];
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
      } else {
        div.innerHTML = "<p class='text-danger'><?= __('Request not found.') ?></p>";
      }
    })
    .catch(err => {
      console.error(err);
      div.innerHTML = "<p class='text-danger'><?= __('Failed to load request details.') ?></p>";
    });
});
</script>
