<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .table thead { background: var(--muted); color: var(--muted-foreground); }
        .table th, .table td { vertical-align: middle; }
        .floating-btn {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 1000;
            background: var(--primary); color: var(--primary-foreground); border: none;
            border-radius: 50%; width: 56px; height: 56px; font-size: 2rem; display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-lg); cursor: pointer;
        }
        .form-section { margin-top: 2rem; max-width: 700px; margin-left: auto; margin-right: auto; }
        .alert { margin-top: 1rem; }
        .filter-row input, .filter-row select { font-size: 0.95em; }
        .pagination { margin-bottom: 0; }
        .select2-container { width: 100% !important; }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">User Management</h2>
    <div class="card-enhanced p-3 mb-4">
        <div class="card-enhanced-header mb-3">
            <h5 class="card-enhanced-title mb-0">Search & Filter Users</h5>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" id="generalSearch" class="form-control" placeholder="Search users...">
            </div>
            <div class="col-md-6 text-end">
                <span id="tableMessage" class="text-success"></span>
            </div>
        </div>
    </div>
    
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h5 class="card-enhanced-title mb-0">User Directory</h5>
        </div>
        <div class="card-enhanced-content p-0">
            <div class="table-responsive">
                <table class="table-enhanced" id="usersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Skills</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                        <tr class="filter-row">
                            <th><input type="text" class="form-control form-control-sm" id="filterName" placeholder="Filter name"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterEmail" placeholder="Filter email"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterPhone" placeholder="Filter phone"></th>
                            <th><select class="form-select form-select-sm" id="filterRole"></select></th>
                            <th><select class="form-select form-select-sm" id="filterStatus"></select></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="usersTbody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div>
                    <label class="text-muted">Show 
                        <select id="recordsPerPage" class="form-select form-select-sm d-inline-block mx-1" style="width:auto;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select> entries
                    </label>
                </div>
                <nav>
                    <ul class="pagination pagination-enhanced pagination-sm mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="form-enhanced mt-4" id="userFormSection" style="display:none;max-width:900px;margin-left:auto;margin-right:auto;">
        <h5 id="formTitle" class="mb-4" style="font-size:1.25rem;font-weight:700;">Add User</h5>
        <form id="userForm" autocomplete="off">
            <input type="hidden" id="userId">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="userName" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="userEmail" required>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="userPhone" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <span class="text-danger" id="passwordRequired">*</span></label>
                    <input type="password" class="form-control" id="userPassword" autocomplete="new-password">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select id="userRole" class="form-select" required></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select id="userStatus" class="form-select" required></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Skills</label>
                    <select id="userSkills" class="form-select" multiple></select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-enhanced btn-primary-enhanced" id="saveUserBtn">Save User</button>
                <button type="button" class="btn-enhanced btn-outline-enhanced" id="cancelFormBtn">Cancel</button>
            </div>
            <div id="formMessage" class="alert-enhanced mt-3" style="display:none;"></div>
        </form>
    </div>
    <button class="floating-btn" id="addUserBtn" title="Add User">+</button>
</div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// --- Full AJAX CRUD for Users & User Skills ---
const apiUrl = '/api/crud/';
let editingId = null, deletingId = null, currentPage = 1, recordsPerPage = 10, isLoading = false;
let roles = [], statuses = [], skills = [];

function showMessage(msg, isError = false) {
    $('#tableMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#tableMessage').fadeOut(); }, 3000);
}
function showFormMessage(msg, isError = false) {
    $('#formMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#formMessage').fadeOut(); }, 3000);
}
function resetForm() {
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#userSkills').val(null).trigger('change');
    editingId = null;
    $('#formTitle').text('Add User');
    $('#saveUserBtn').text('Save');
    $('#formMessage').text('');
    $('#passwordRequired').show();
}
function getFilters() {
    return {
        name: $('#filterName').val(),
        email: $('#filterEmail').val(),
        phone: $('#filterPhone').val(),
        role_id: $('#filterRole').val(),
        status_id: $('#filterStatus').val(),
        search: $('#generalSearch').val()
    };
}
function loadCombos() {
    // Roles
    $.post(apiUrl + 'filter', { table: 'fms_lookup_values', filters: { type: 'Role' } }, function(res) {
        roles = res || [];
        let opts = '<option value="">All</option>';
        roles.forEach(r => opts += `<option value="${r.id}">${r.name}</option>`);
        $('#filterRole, #userRole').html(opts);
    });
    // Statuses
    $.post(apiUrl + 'filter', { table: 'fms_lookup_values', filters: { type: 'Status' } }, function(res) {
        statuses = res || [];
        let opts = '<option value="">All</option>';
        statuses.forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
        $('#filterStatus, #userStatus').html(opts);
    });
    // Skills
    $.post(apiUrl + 'filter', { table: 'fms_technician_skills', filters: {} }, function(res) {
        skills = res || [];
        let opts = '';
        skills.forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
        $('#userSkills').html(opts).select2({ placeholder: 'Select skills', allowClear: true });
    });
}
function loadTable(page = 1) {
    if (isLoading) return;
    isLoading = true;
    $('#usersTbody').html('<tr><td colspan="9" class="text-center">Loading...</td></tr>');
    let filters = getFilters();
    let req = {
        table: 'fms_users',
        filters: {
            p_id: null,
            p_name: filters.name || null,
            p_email: filters.email || null,
            p_phone: filters.phone || null,
            p_role_id: filters.role_id || null,
            p_status_id: filters.status_id || null,
            p_search: filters.search || null
        },
        page: page,
        per_page: recordsPerPage
    };
    $.post(apiUrl + 'filter', req, function(res) {
        isLoading = false;
        let rows = '';
        if (res && res.length) {
            res.forEach(function(row) {
                let role = roles.find(r => r.id == row.role_id)?.name || '';
                let status = statuses.find(s => s.id == row.status_id)?.name || '';
                let skillNames = (row.skills || '').split(',').filter(Boolean).join(', ');
                rows += `<tr data-id="${row.id}">
                    <td>${row.name || ''}</td>
                    <td>${row.email || ''}</td>
                    <td>${row.phone || ''}</td>
                    <td>${role}</td>
                    <td>${status}</td>
                    <td>${skillNames}</td>
                    <td>${row.created_at || ''}</td>
                    <td>${row.updated_at || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary editBtn" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger deleteBtn" title="Delete"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            });
        } else {
            rows = '<tr><td colspan="9" class="text-center">No users found.</td></tr>';
        }
        $('#usersTbody').html(rows);
    }).fail(function() {
        isLoading = false;
        $('#usersTbody').html('<tr><td colspan="9" class="text-danger text-center">Error loading data</td></tr>');
    });
}
$('#addUserBtn').on('click', function() {
    resetForm();
    $('#userFormSection').show();
    $('html,body').animate({scrollTop: $('#userFormSection').offset().top-40}, 300);
});
$('#cancelFormBtn').on('click', function() {
    resetForm();
    $('#userFormSection').hide();
});
$('#userForm').on('submit', function(e) {
    e.preventDefault();
    let data = {
        name: $('#userName').val().trim(),
        email: $('#userEmail').val().trim(),
        phone: $('#userPhone').val().trim(),
        password: $('#userPassword').val(),
        role_id: $('#userRole').val(),
        status_id: $('#userStatus').val(),
        skills: $('#userSkills').val() || []
    };
    let id = $('#userId').val();
    let url = apiUrl + (id ? 'update' : 'create');
    let req = { table: 'fms_users', data: data };
    if (id) req.data.id = id;
    // Validation: unique email/phone
    $.post(apiUrl + 'filter', { table: 'fms_users', filters: { p_email: data.email } }, function(res) {
        if (res && res.length && (!id || res[0].id != id)) {
            showFormMessage('Email must be unique.', true); return;
        }
        $.post(apiUrl + 'filter', { table: 'fms_users', filters: { p_phone: data.phone } }, function(res2) {
            if (res2 && res2.length && (!id || res2[0].id != id)) {
                showFormMessage('Phone must be unique.', true); return;
            }
            // Password required on add, optional on edit
            if (!id && !data.password) { showFormMessage('Password is required.', true); return; }
            if (id && !data.password) delete req.data.password;
            // Save user
            $.post(url, req, function(resp) {
                let userId = resp.id || id;
                // Save user skills
                $.post(apiUrl + 'delete', { table: 'fms_user_skills', data: { user_id: userId } }, function() {
                    let skillReqs = (data.skills || []).map(skillId => $.post(apiUrl + 'create', { table: 'fms_user_skills', data: { user_id: userId, skill_id: skillId } }));
                    $.when.apply($, skillReqs).then(function() {
                        showFormMessage('User saved successfully.');
                        resetForm();
                        loadTable(currentPage);
                    });
                });
            }).fail(function(xhr) {
                showFormMessage('Error saving user.', true);
            });
        });
    });
});
$('#usersTbody').on('click', '.editBtn', function() {
    let tr = $(this).closest('tr');
    let id = tr.data('id');
    editingId = id;
    $.post(apiUrl + 'filter', { table: 'fms_users', filters: { p_id: id } }, function(res) {
        if (res && res.length) {
            let u = res[0];
            $('#userId').val(u.id);
            $('#userName').val(u.name);
            $('#userEmail').val(u.email);
            $('#userPhone').val(u.phone);
            $('#userRole').val(u.role_id);
            $('#userStatus').val(u.status_id);
            $('#userPassword').val('');
            $('#passwordRequired').hide();
            // Load user skills
            $.post(apiUrl + 'filter', { table: 'fms_user_skills', filters: { user_id: id } }, function(sk) {
                let skillIds = (sk || []).map(s => s.skill_id);
                $('#userSkills').val(skillIds).trigger('change');
            });
            $('#formTitle').text('Edit User');
            $('#saveUserBtn').text('Update');
            $('#userFormSection').show();
            $('html,body').animate({scrollTop: $('#userFormSection').offset().top-40}, 300);
        }
    });
});
let deleteId = null;
$('#usersTbody').on('click', '.deleteBtn', function() {
    let tr = $(this).closest('tr');
    deleteId = tr.data('id');
    $('#confirmDeleteModal').modal('show');
});
$('#confirmDeleteBtn').on('click', function() {
    if (!deleteId) return;
    $.post(apiUrl + 'delete', { table: 'fms_users', data: { id: deleteId } }, function(resp) {
        showMessage('User deleted successfully.');
        loadTable(currentPage);
        $('#confirmDeleteModal').modal('hide');
    }).fail(function(xhr) {
        showMessage('Error deleting user.', true);
    });
});
$('#generalSearch, #filterName, #filterEmail, #filterPhone, #filterRole, #filterStatus').on('input change', function() {
    loadTable(1);
});
$('#recordsPerPage').on('change', function() {
    recordsPerPage = $(this).val();
    loadTable(1);
});
$(function() {
    loadCombos();
    loadTable();
});
</script>
</body>
</html>
