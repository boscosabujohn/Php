<?php include_once __DIR__ . '/../../lang.php'; ?>
<?php include_once __DIR__ . '/partials/lang_switcher.php'; ?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>" dir="<?= ($_SESSION['lang'] ?? 'en') === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $L['role_permissions_management'] ?? 'Role Permissions Management' ?></title>
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
    <div class="d-flex justify-content-end mb-2">
        <?php include __DIR__ . '/partials/lang_switcher.php'; ?>
    </div>
    <h2 class="mb-4" style="font-size:1.4rem;"> <?= $L['role_permissions_management'] ?? 'Role Permissions Management' ?> </h2>
    <div class="card p-2 mb-3">
        <div class="row g-2 align-items-center mb-2">
            <div class="col-md-4">
                <input type="text" id="generalSearch" class="form-control" placeholder="<?= $L['search_permissions'] ?? 'Search permissions...' ?>" style="font-size:1em;">
            </div>
            <div class="col-md-8 text-end">
                <span id="tableMessage" class="text-success"></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="permissionsTable" style="font-size:0.98em;">
                <thead>
                    <tr>
                        <th><?= $L['role'] ?? 'Role' ?></th>
                        <th><?= $L['route'] ?? 'Route' ?></th>
                        <th><?= $L['can_view'] ?? 'Can View' ?></th>
                        <th><?= $L['can_add'] ?? 'Can Add' ?></th>
                        <th><?= $L['can_edit'] ?? 'Can Edit' ?></th>
                        <th><?= $L['can_delete'] ?? 'Can Delete' ?></th>
                        <th><?= $L['created_by'] ?? 'Created By' ?></th>
                        <th><?= $L['updated_by'] ?? 'Updated By' ?></th>
                        <th><?= $L['updated_at'] ?? 'Updated At' ?></th>
                        <th style="width:110px;"> <?= $L['actions'] ?? 'Actions' ?> </th>
                    </tr>
                    <tr class="filter-row">
                        <th><select class="form-select form-select-sm" id="filterRole"></select></th>
                        <th><input type="text" class="form-control form-control-sm" id="filterRoute" placeholder="<?= $L['filter_route'] ?? 'Filter route' ?>"></th>
                        <th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
                    </tr>
                </thead>
                <tbody id="permissionsTbody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div>
                <label><?= $L['show'] ?? 'Show' ?> <select id="recordsPerPage" class="form-select form-select-sm d-inline-block" style="width:auto;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select> <?= $L['entries'] ?? 'entries' ?></label>
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
    <div class="card p-2 form-section" id="permissionFormSection" style="display:none;">
        <h5 id="formTitle" style="font-size:1.1rem;"> <?= $L['add_permission'] ?? 'Add Permission' ?> </h5>
        <form id="permissionForm" autocomplete="off">
            <input type="hidden" id="permissionId">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label mb-1"> <?= $L['role'] ?? 'Role' ?> <span class="text-danger">*</span></label>
                    <select id="permissionRole" class="form-select" required></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-1"> <?= $L['route'] ?? 'Route' ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="permissionRoute" required>
                </div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-3"><label class="form-label mb-1"> <?= $L['can_view'] ?? 'Can View' ?> </label><input type="checkbox" id="canView" class="form-check-input ms-2"></div>
                <div class="col-md-3"><label class="form-label mb-1"> <?= $L['can_add'] ?? 'Can Add' ?> </label><input type="checkbox" id="canAdd" class="form-check-input ms-2"></div>
                <div class="col-md-3"><label class="form-label mb-1"> <?= $L['can_edit'] ?? 'Can Edit' ?> </label><input type="checkbox" id="canEdit" class="form-check-input ms-2"></div>
                <div class="col-md-3"><label class="form-label mb-1"> <?= $L['can_delete'] ?? 'Can Delete' ?> </label><input type="checkbox" id="canDelete" class="form-check-input ms-2"></div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="savePermissionBtn"> <?= $L['save'] ?? 'Save' ?> </button>
                <button type="button" class="btn btn-secondary" id="cancelFormBtn"> <?= $L['cancel'] ?? 'Cancel' ?> </button>
            </div>
            <div id="formMessage"></div>
        </form>
    </div>
    <button class="floating-btn" id="addPermissionBtn" title="<?= $L['add_permission'] ?? 'Add Permission' ?>">+</button>
</div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel"> <?= $L['confirm_delete'] ?? 'Confirm Delete' ?> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?= $L['are_you_sure_delete_permission'] ?? 'Are you sure you want to delete this permission?' ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <?= $L['cancel'] ?? 'Cancel' ?> </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn"> <?= $L['delete'] ?? 'Delete' ?> </button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const apiUrl = '/api/crud/';
let editingId = null, deletingId = null, currentPage = 1, recordsPerPage = 10, isLoading = false;
let roles = [];
function showMessage(msg, isError = false) {
    $('#tableMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#tableMessage').fadeOut(); }, 3000);
}
function showFormMessage(msg, isError = false) {
    $('#formMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#formMessage').fadeOut(); }, 3000);
}
function resetForm() {
    $('#permissionForm')[0].reset();
    $('#permissionId').val('');
    editingId = null;
    $('#formTitle').text('Add Permission');
    $('#savePermissionBtn').text('Save');
    $('#formMessage').text('');
}
function loadRolesCombo() {
    $.post(apiUrl + 'filter', { table: 'fms_lookup_values', filters: { type: 'Role' } }, function(res) {
        roles = res || [];
        let opts = '<option value="">All</option>';
        roles.forEach(r => opts += `<option value="${r.id}">${r.name}</option>`);
        $('#filterRole, #permissionRole').html(opts);
    });
}
function loadTable(page = 1) {
    if (isLoading) return;
    isLoading = true;
    $('#permissionsTbody').html('<tr><td colspan="10" class="text-center">Loading...</td></tr>');
    let req = {
        table: 'fms_role_permissions',
        filters: {
            p_id: null,
            p_role_id: $('#filterRole').val() || null,
            p_route: $('#filterRoute').val() || null,
            p_search: $('#generalSearch').val() || null
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
                rows += `<tr data-id="${row.id}">
                    <td>${role}</td>
                    <td>${row.route || ''}</td>
                    <td><input type="checkbox" disabled ${row.can_view==1?'checked':''}></td>
                    <td><input type="checkbox" disabled ${row.can_add==1?'checked':''}></td>
                    <td><input type="checkbox" disabled ${row.can_edit==1?'checked':''}></td>
                    <td><input type="checkbox" disabled ${row.can_delete==1?'checked':''}></td>
                    <td>${row.created_by || ''}</td>
                    <td>${row.updated_by || ''}</td>
                    <td>${row.updated_at || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary editBtn" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger deleteBtn" title="Delete"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            });
        } else {
            rows = '<tr><td colspan="10" class="text-center">No permissions found.</td></tr>';
        }
        $('#permissionsTbody').html(rows);
    }).fail(function() {
        isLoading = false;
        $('#permissionsTbody').html('<tr><td colspan="10" class="text-danger text-center">Error loading data</td></tr>');
    });
}
$('#addPermissionBtn').on('click', function() {
    resetForm();
    $('#permissionFormSection').show();
    $('html,body').animate({scrollTop: $('#permissionFormSection').offset().top-40}, 300);
});
$('#cancelFormBtn').on('click', function() {
    resetForm();
    $('#permissionFormSection').hide();
});
$('#permissionForm').on('submit', function(e) {
    e.preventDefault();
    let data = {
        role_id: $('#permissionRole').val(),
        route: $('#permissionRoute').val().trim(),
        can_view: $('#canView').is(':checked') ? 1 : 0,
        can_add: $('#canAdd').is(':checked') ? 1 : 0,
        can_edit: $('#canEdit').is(':checked') ? 1 : 0,
        can_delete: $('#canDelete').is(':checked') ? 1 : 0
    };
    let id = $('#permissionId').val();
    let url = apiUrl + (id ? 'update' : 'create');
    let req = { table: 'fms_role_permissions', data: data };
    if (id) req.data.id = id;
    // Unique role+route validation
    $.post(apiUrl + 'filter', { table: 'fms_role_permissions', filters: { role_id: data.role_id, route: data.route } }, function(res) {
        if (res && res.length && (!id || res[0].id != id)) {
            showFormMessage('This role already has permissions for this route.', true); return;
        }
        if (!data.role_id || !data.route) { showFormMessage('All required fields must be filled.', true); return; }
        $.post(url, req, function(resp) {
            showFormMessage('Permission saved successfully.');
            resetForm();
            loadTable(currentPage);
        }).fail(function(xhr) {
            showFormMessage('Error saving permission.', true);
        });
    });
});
$('#permissionsTbody').on('click', '.editBtn', function() {
    let tr = $(this).closest('tr');
    let id = tr.data('id');
    editingId = id;
    $.post(apiUrl + 'filter', { table: 'fms_role_permissions', filters: { p_id: id } }, function(res) {
        if (res && res.length) {
            let p = res[0];
            $('#permissionId').val(p.id);
            $('#permissionRole').val(p.role_id);
            $('#permissionRoute').val(p.route);
            $('#canView').prop('checked', p.can_view==1);
            $('#canAdd').prop('checked', p.can_add==1);
            $('#canEdit').prop('checked', p.can_edit==1);
            $('#canDelete').prop('checked', p.can_delete==1);
            $('#formTitle').text('Edit Permission');
            $('#savePermissionBtn').text('Update');
            $('#permissionFormSection').show();
            $('html,body').animate({scrollTop: $('#permissionFormSection').offset().top-40}, 300);
        }
    });
});
let deleteId = null;
$('#permissionsTbody').on('click', '.deleteBtn', function() {
    let tr = $(this).closest('tr');
    deleteId = tr.data('id');
    $('#confirmDeleteModal').modal('show');
});
$('#confirmDeleteBtn').on('click', function() {
    if (!deleteId) return;
    $.post(apiUrl + 'delete', { table: 'fms_role_permissions', data: { id: deleteId } }, function(resp) {
        showMessage('Permission deleted successfully.');
        loadTable(currentPage);
        $('#confirmDeleteModal').modal('hide');
    }).fail(function(xhr) {
        showMessage('Error deleting permission.', true);
    });
});
$('#generalSearch, #filterRole, #filterRoute').on('input change', function() {
    loadTable(1);
});
$('#recordsPerPage').on('change', function() {
    recordsPerPage = $(this).val();
    loadTable(1);
});
$(function() {
    loadRolesCombo();
    loadTable();
});
</script>
</body>
</html>
