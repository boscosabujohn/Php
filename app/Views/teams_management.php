<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams Management</title>
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
        .nested-table-section { margin-top: 1.5rem; }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Teams Management</h2>
    <div class="card-enhanced p-3 mb-4">
        <div class="card-enhanced-header mb-3">
            <h5 class="card-enhanced-title mb-0">Search & Filter Teams</h5>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" id="generalSearch" class="form-control" placeholder="Search teams...">
            </div>
            <div class="col-md-6 text-end">
                <span id="tableMessage" class="text-success"></span>
            </div>
        </div>
    </div>
    
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h5 class="card-enhanced-title mb-0">Team Directory</h5>
        </div>
        <div class="card-enhanced-content p-0">
            <div class="table-responsive">
                <table class="table-enhanced" id="teamsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Supervisor</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                        <tr class="filter-row">
                            <th><input type="text" class="form-control form-control-sm" id="filterName" placeholder="Filter name"></th>
                            <th><select class="form-select form-select-sm" id="filterSupervisor"></select></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="teamsTbody">
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
    <div class="form-enhanced mt-4" id="teamFormSection" style="display:none;max-width:800px;margin-left:auto;margin-right:auto;">
        <h5 id="formTitle" class="mb-4" style="font-size:1.25rem;font-weight:700;">Add Team</h5>
        <form id="teamForm" autocomplete="off">
            <input type="hidden" id="teamId">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Team Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="teamName" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Supervisor <span class="text-danger">*</span></label>
                    <select id="teamSupervisor" class="form-select" required></select>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-12">
                    <label class="form-label">Team Members (Technicians)</label>
                    <select id="teamMembers" class="form-select" multiple></select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-enhanced btn-primary-enhanced" id="saveTeamBtn">Save Team</button>
                <button type="button" class="btn-enhanced btn-outline-enhanced" id="cancelFormBtn">Cancel</button>
            </div>
            <div id="formMessage" class="alert-enhanced mt-3" style="display:none;"></div>
        </form>
    </div>
    <button class="floating-btn" id="addTeamBtn" title="Add Team">+</button>
    <div class="nested-table-section" id="teamMembersSection" style="display:none;">
        <div class="card p-2 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" id="selectedTeamTitle">Team Members</h5>
                <span id="membersTableMessage" class="text-success"></span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="membersTable" style="font-size:0.98em;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="membersTbody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
        Are you sure you want to delete this team?
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
const apiUrl = '/api/crud/';
let editingId = null, deletingId = null, currentPage = 1, recordsPerPage = 10, isLoading = false, selectedTeamId = null;
let supervisors = [], technicians = [];
function showMessage(msg, isError = false) {
    $('#tableMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#tableMessage').fadeOut(); }, 3000);
}
function showFormMessage(msg, isError = false) {
    $('#formMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#formMessage').fadeOut(); }, 3000);
}
function showMembersMessage(msg, isError = false) {
    $('#membersTableMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
    setTimeout(() => { $('#membersTableMessage').fadeOut(); }, 3000);
}
function resetForm() {
    $('#teamForm')[0].reset();
    $('#teamId').val('');
    $('#teamMembers').val(null).trigger('change');
    editingId = null;
    $('#formTitle').text('Add Team');
    $('#saveTeamBtn').text('Save');
    $('#formMessage').text('');
}
function loadCombos() {
    // Supervisors
    $.post(apiUrl + 'filter', { table: 'fms_users', filters: { role: 'Supervisor' } }, function(res) {
        supervisors = res || [];
        let opts = '<option value="">All</option>';
        supervisors.forEach(s => opts += `<option value="${s.id}">${s.name}</option>`);
        $('#filterSupervisor, #teamSupervisor').html(opts);
    });
    // Technicians
    $.post(apiUrl + 'filter', { table: 'fms_users', filters: { role: 'Technician' } }, function(res) {
        technicians = res || [];
        let opts = '';
        technicians.forEach(t => opts += `<option value="${t.id}">${t.name}</option>`);
        $('#teamMembers').html(opts).select2({ placeholder: 'Select technicians', allowClear: true });
    });
}
function loadTable(page = 1) {
    if (isLoading) return;
    isLoading = true;
    $('#teamsTbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
    let req = {
        table: 'fms_teams',
        filters: {
            p_id: null,
            p_name: $('#filterName').val() || null,
            p_supervisor_id: $('#filterSupervisor').val() || null,
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
                let supervisor = supervisors.find(s => s.id == row.supervisor_id)?.name || '';
                rows += `<tr data-id="${row.id}" class="team-row${selectedTeamId==row.id?' table-primary':''}">
                    <td>${row.name || ''}</td>
                    <td>${supervisor}</td>
                    <td>${row.created_at || ''}</td>
                    <td>${row.updated_at || ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary editBtn" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger deleteBtn" title="Delete"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            });
        } else {
            rows = '<tr><td colspan="5" class="text-center">No teams found.</td></tr>';
        }
        $('#teamsTbody').html(rows);
    }).fail(function() {
        isLoading = false;
        $('#teamsTbody').html('<tr><td colspan="5" class="text-danger text-center">Error loading data</td></tr>');
    });
}
$('#addTeamBtn').on('click', function() {
    resetForm();
    $('#teamFormSection').show();
    $('html,body').animate({scrollTop: $('#teamFormSection').offset().top-40}, 300);
});
$('#cancelFormBtn').on('click', function() {
    resetForm();
    $('#teamFormSection').hide();
});
$('#teamForm').on('submit', function(e) {
    e.preventDefault();
    let data = {
        name: $('#teamName').val().trim(),
        supervisor_id: $('#teamSupervisor').val(),
        members: $('#teamMembers').val() || []
    };
    let id = $('#teamId').val();
    let url = apiUrl + (id ? 'update' : 'create');
    let req = { table: 'fms_teams', data: data };
    if (id) req.data.id = id;
    // Unique name validation
    $.post(apiUrl + 'filter', { table: 'fms_teams', filters: { p_name: data.name } }, function(res) {
        if (res && res.length && (!id || res[0].id != id)) {
            showFormMessage('Team name must be unique.', true); return;
        }
        if (!data.name || !data.supervisor_id) { showFormMessage('All required fields must be filled.', true); return; }
        $.post(url, req, function(resp) {
            let teamId = resp.id || id;
            // Save team members
            $.post(apiUrl + 'delete', { table: 'fms_team_members', data: { team_id: teamId } }, function() {
                let memberReqs = (data.members || []).map(techId => $.post(apiUrl + 'create', { table: 'fms_team_members', data: { team_id: teamId, technician_id: techId } }));
                $.when.apply($, memberReqs).then(function() {
                    showFormMessage('Team saved successfully.');
                    resetForm();
                    loadTable(currentPage);
                });
            });
        }).fail(function(xhr) {
            showFormMessage('Error saving team.', true);
        });
    });
});
$('#teamsTbody').on('click', '.editBtn', function(e) {
    e.stopPropagation();
    let tr = $(this).closest('tr');
    let id = tr.data('id');
    editingId = id;
    $.post(apiUrl + 'filter', { table: 'fms_teams', filters: { p_id: id } }, function(res) {
        if (res && res.length) {
            let t = res[0];
            $('#teamId').val(t.id);
            $('#teamName').val(t.name);
            $('#teamSupervisor').val(t.supervisor_id);
            // Load team members
            $.post(apiUrl + 'filter', { table: 'fms_team_members', filters: { team_id: id } }, function(members) {
                let memberIds = (members || []).map(m => m.technician_id);
                $('#teamMembers').val(memberIds).trigger('change');
            });
            $('#formTitle').text('Edit Team');
            $('#saveTeamBtn').text('Update');
            $('#teamFormSection').show();
            $('html,body').animate({scrollTop: $('#teamFormSection').offset().top-40}, 300);
            // Show members table
            selectedTeamId = id;
            loadMembersTable(id);
        }
    });
});
let deleteId = null;
$('#teamsTbody').on('click', '.deleteBtn', function(e) {
    e.stopPropagation();
    let tr = $(this).closest('tr');
    deleteId = tr.data('id');
    $('#confirmDeleteModal').modal('show');
});
$('#confirmDeleteBtn').on('click', function() {
    if (!deleteId) return;
    $.post(apiUrl + 'delete', { table: 'fms_teams', data: { id: deleteId } }, function(resp) {
        showMessage('Team deleted successfully.');
        selectedTeamId = null;
        loadTable(currentPage);
        $('#confirmDeleteModal').modal('hide');
        $('#teamMembersSection').hide();
    }).fail(function(xhr) {
        showMessage('Error deleting team.', true);
    });
});
$('#teamsTbody').on('click', 'tr.team-row', function() {
    let id = $(this).data('id');
    if (selectedTeamId == id) {
        selectedTeamId = null;
        $('#teamMembersSection').hide();
        loadTable(currentPage);
    } else {
        selectedTeamId = id;
        loadMembersTable(id);
    }
});
function loadMembersTable(teamId) {
    $('#teamMembersSection').show();
    $('#selectedTeamTitle').text('Team Members');
    $('#membersTbody').html('<tr><td colspan="4">Loading...</td></tr>');
    $.post(apiUrl + 'filter', { table: 'fms_team_members', filters: { team_id: teamId } }, function(res) {
        let rows = '';
        if (res && res.length) {
            res.forEach(function(m) {
                let tech = technicians.find(t => t.id == m.technician_id) || {};
                rows += `<tr data-id="${m.technician_id}">
                    <td>${tech.name || ''}</td>
                    <td>${tech.email || ''}</td>
                    <td>${tech.phone || ''}</td>
                    <td><button class="btn btn-sm btn-outline-danger removeMemberBtn" title="Remove"><i class="bi bi-trash"></i></button></td>
                </tr>`;
            });
        } else {
            rows = '<tr><td colspan="4" class="text-center">No members found.</td></tr>';
        }
        $('#membersTbody').html(rows);
    });
}
$('#membersTbody').on('click', '.removeMemberBtn', function() {
    let techId = $(this).closest('tr').data('id');
    if (!selectedTeamId || !techId) return;
    $.post(apiUrl + 'delete', { table: 'fms_team_members', data: { team_id: selectedTeamId, technician_id: techId } }, function() {
        showMembersMessage('Member removed.');
        loadMembersTable(selectedTeamId);
        // Also update the multi-select
        let current = $('#teamMembers').val() || [];
        $('#teamMembers').val(current.filter(id => id != techId)).trigger('change');
    });
});
$('#generalSearch, #filterName, #filterSupervisor').on('input change', function() {
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
