<?php
// File: app/views/attachments/view.php
?>
<div class="container py-4">
  <h4><?= __('Attachment Details') ?></h4>
  <div id="attachmentDetails" class="mt-3"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const id = location.pathname.split("/").pop();
  const div = document.getElementById("attachmentDetails");
  div.innerHTML = "<p class='text-muted'><?= __('Loading...') ?></p>";
  fetch(`/api/crud?table=fms_request_attachments&op=filter&p_id=${id}`)
    .then(r => r.json())
    .then(res => {
      if (res.status === 200 && res.data.length === 1) {
        const d = res.data[0];
        div.innerHTML = `
          <table class="table table-bordered">
            <tr><th><?= __('ID') ?></th><td>${d.id}</td></tr>
            <tr><th><?= __('Request ID') ?></th><td>${d.request_id}</td></tr>
            <tr><th><?= __('File Path') ?></th><td>${d.file_path ? `<a href="${d.file_path}" target="_blank">${d.file_path}</a>` : ''}</td></tr>
            <tr><th><?= __('Uploaded By') ?></th><td>${d.uploaded_by_name ?? d.uploaded_by}</td></tr>
            <tr><th><?= __('Uploaded At') ?></th><td>${d.uploaded_at ?? ''}</td></tr>
          </table>
        `;
      } else {
        div.innerHTML = "<p class='text-danger'><?= __('Attachment not found.') ?></p>";
      }
    })
    .catch(err => {
      console.error(err);
      div.innerHTML = "<p class='text-danger'><?= __('Failed to load attachment.') ?></p>";
    });
});
</script>
