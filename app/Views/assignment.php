<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .form-section { margin-top: 2rem; max-width: 700px; margin-left: auto; margin-right: auto; }
        .alert { margin-top: 1rem; }
        .select2-container { width: 100% !important; }
        .checklist-table th, .checklist-table td { vertical-align: middle; }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Assignment</h2>
    <div class="card p-2 form-section mb-4">
        <h5 style="font-size:1.1rem;">Assign Request</h5>
        <form id="assignmentForm" autocomplete="off">
            <input type="hidden" id="requestId">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label mb-1">Assign To <span class="text-danger">*</span></label>
                    <select id="assigneeId" class="form-select" required></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-1">Due Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dueDate" required>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="saveAssignmentBtn">Assign</button>
            </div>
            <div id="formMessage"></div>
        </form>
    </div>
    <div class="card p-2 mb-4">
        <h5 class="mb-3">Checklist Items (Update Status)</h5>
        <div class="table-responsive">
            <table class="table table-bordered checklist-table align-middle" id="checklistTable" style="font-size:0.98em;">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="checklistTbody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="card p-2 mb-4">
        <h5 class="mb-3">Assignment Evidence (Upload)</h5>
        <form id="evidenceForm" enctype="multipart/form-data">
            <input type="hidden" id="evidenceRequestId">
            <div class="mb-2">
                <input type="file" id="evidenceFiles" name="files[]" multiple class="form-control">
            </div>
            <button type="submit" class="btn btn-secondary btn-sm">Upload Evidence</button>
            <div id="evidenceMessage"></div>
        </form>
        <div id="evidenceList" class="mt-2"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const apiUrl = '/api/crud/';
let users = [], checklist = [], requestId = null;
function showFormMessage(msg, isError = false) {
    $('#formMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#formMessage').fadeOut(); }, 3000);
}
function loadUsers() {
    $.post(apiUrl + 'filter', { table: 'fms_users', filters: { role: 'Technician' } }, function(res) {
        users = res || [];
        let opts = '<option value="">Select Technician</option>';
        users.forEach(u => opts += `<option value="${u.id}">${u.name}</option>`);
        $('#assigneeId').html(opts).select2({ placeholder: 'Select Technician', allowClear: true });
    });
}
function loadAssignment() {
    // Get requestId from URL
    let parts = window.location.pathname.split('/');
    requestId = parts[parts.length-1];
    $('#requestId').val(requestId);
    // Load assignment if exists
    $.post(apiUrl + 'filter', { table: 'fms_assignments', filters: { request_id: requestId } }, function(res) {
        if (res && res.length) {
            let a = res[0];
            $('#assigneeId').val(a.technician_id).trigger('change');
            $('#dueDate').val(a.due_date);
            $('#saveAssignmentBtn').text('Update Assignment');
        }
    });
    loadChecklist();
}
function loadChecklist() {
    $.post(apiUrl + 'filter', { table: 'fms_request_checklist_items', filters: { request_id: requestId } }, function(res) {
        checklist = res || [];
        let rows = '';
        checklist.forEach(function(item) {
            rows += `<tr><td>${item.item || ''}</td><td>${item.status || 'Pending'}</td><td>` +
                (item.status !== 'Completed' ? `<button class="btn btn-sm btn-success completeItemBtn" data-id="${item.id}">Mark Complete</button>` : '') +
                `</td></tr>`;
        });
        if (!rows) rows = '<tr><td colspan="3" class="text-center">No checklist items found.</td></tr>';
        $('#checklistTbody').html(rows);
    });
}
$('#checklistTbody').on('click', '.completeItemBtn', function() {
    let itemId = $(this).data('id');
    $.post(apiUrl + 'update', { table: 'fms_request_checklist_items', data: { id: itemId, status: 'Completed' } }, function() {
        loadChecklist();
    });
});
$('#assignmentForm').on('submit', function(e) {
    e.preventDefault();
    let data = {
        request_id: requestId,
        technician_id: $('#assigneeId').val(),
        due_date: $('#dueDate').val()
    };
    if (!data.technician_id || !data.due_date) { showFormMessage('All fields are required.', true); return; }
    // Check if assignment exists
    $.post(apiUrl + 'filter', { table: 'fms_assignments', filters: { request_id: requestId } }, function(res) {
        let url = apiUrl + (res && res.length ? 'update' : 'create');
        if (res && res.length) data.id = res[0].id;
        $.post(url, { table: 'fms_assignments', data }, function(resp) {
            showFormMessage('Assignment saved successfully.');
            // On create, copy checklist template if not already present
            if (!(res && res.length)) {
                $.post(apiUrl + 'filter', { table: 'checklist_templates', filters: { category_id: null } }, function(templates) {
                    // You may want to pass category_id from request for real use
                    (templates || []).forEach(function(tmpl) {
                        $.post(apiUrl + 'create', { table: 'fms_request_checklist_items', data: { request_id: requestId, item: tmpl.item, status: 'Pending' } });
                    });
                    setTimeout(loadChecklist, 500);
                });
            } else {
                loadChecklist();
            }
        });
    });
});
$('#evidenceForm').on('submit', function(e) {
    e.preventDefault();
    let files = $('#evidenceFiles')[0].files;
    if (!files.length) { $('#evidenceMessage').text('Select files to upload.').addClass('text-danger'); return; }
    let formData = new FormData();
    formData.append('request_id', requestId);
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    $.ajax({
        url: apiUrl + 'create',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function() {
            $('#evidenceMessage').text('Evidence uploaded.').removeClass('text-danger').addClass('text-success');
            $('#evidenceFiles').val('');
            loadEvidence();
        },
        error: function() {
            $('#evidenceMessage').text('Error uploading evidence.').addClass('text-danger');
        }
    });
});
function loadEvidence() {
    $.post(apiUrl + 'filter', { table: 'fms_request_attachments', filters: { request_id: requestId } }, function(res) {
        let html = '';
        (res || []).forEach(function(f) {
            html += `<div><a href="/uploads/${f.file_path}" target="_blank">${f.file_name || f.file_path}</a></div>`;
        });
        $('#evidenceList').html(html || '<div class="text-muted">No evidence uploaded.</div>');
    });
}
$(function() {
    loadUsers();
    loadAssignment();
    loadEvidence();
});
</script>
</body>
</html>
