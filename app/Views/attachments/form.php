<?php
// File: app/views/attachments/form.php
?>
<div class="container py-4">
  <h4><?= __('Upload Attachment') ?></h4>
  <form id="attachmentForm" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $id ?? '' ?>">
    <input type="hidden" name="table" value="fms_request_attachments">
    <input type="hidden" name="op" value="create">
    <div class="mb-3">
      <label class="form-label"><?= __('Request ID') ?></label>
      <input type="number" name="request_id" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label"><?= __('File') ?></label>
      <input type="file" name="file_path" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label"><?= __('Uploaded By') ?></label>
      <input type="number" name="uploaded_by" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('Submit') ?></button>
  </form>
  <div id="attachmentResult" class="mt-3"></div>
</div>

<script>
document.getElementById('attachmentForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = e.target;
  const formData = new FormData(form);
  fetch('/api/crud', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(res => {
    document.getElementById('attachmentResult').innerHTML = `<div class="alert alert-${res.status === 200 ? 'success' : 'danger'}">${res.message}</div>`;
    if (res.status === 200) form.reset();
  })
  .catch(err => {
    console.error(err);
    document.getElementById('attachmentResult').innerHTML = `<div class="alert alert-danger"><?= __('Upload failed.') ?></div>`;
  });
});
</script>
