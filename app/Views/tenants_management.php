<!-- Tenant Management UI for FMS (CRUD, Filtering, Confirmation, Themed) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Management</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-section { margin-top: 2rem; }
        .select2-container { width: 100% !important; }
        .alert { margin-top: 1rem; }
        .filter-row input, .filter-row select { font-size: 0.95em; }
        .pagination { margin-bottom: 0; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<?php $session = session(); $roles = $session->get('roles') ?? []; $isGuest = in_array('guest', array_map('strtolower', $roles)); ?>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Tenant Management</h2>
    <?php if (!$isGuest): ?>
    <div class="card-enhanced p-3 mb-4">
        <div class="card-enhanced-header mb-3">
            <h5 class="card-enhanced-title mb-0">Search & Filter</h5>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" id="generalSearch" class="form-control" placeholder="Search tenants..." style="font-size:1em;">
            </div>
            <div class="col-md-6 text-end">
                <span id="tableMessage" class="text-success"></span>
            </div>
        </div>
    </div>
    
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h5 class="card-enhanced-title mb-0">Tenant Directory</h5>
        </div>
        <div class="card-enhanced-content p-0">
            <div class="table-responsive">
                <table class="table-enhanced" id="tenantsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Property</th>
                            <th>Flat/Office No.</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                        <tr class="filter-row">
                            <th><input type="text" class="form-control form-control-sm" id="filterName" placeholder="Filter name"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterProperty" placeholder="Filter property"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterFlat" placeholder="Filter flat/office"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterEmail" placeholder="Filter email"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterAddress" placeholder="Filter address"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tenantsTbody">
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
    <?php endif; ?>
    <div class="form-enhanced mt-4" id="tenantFormSection" style="max-width:800px;margin-left:auto;margin-right:auto;">
        <h5 id="formTitle" class="mb-4" style="font-size:1.25rem;font-weight:700;">Add Tenant</h5>
        <form id="tenantForm" autocomplete="off">
            <input type="hidden" id="tenantId">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tenantName" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Property <span class="text-danger">*</span></label>
                    <select id="tenantProperty" class="form-select" required></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Flat/Office No.</label>
                    <input type="text" class="form-control" id="tenantFlat">
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="tenantEmail" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" id="tenantAddress" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-enhanced btn-primary-enhanced" id="saveTenantBtn">Save Tenant</button>
                <button type="button" class="btn-enhanced btn-outline-enhanced" id="cancelFormBtn">Cancel</button>
            </div>
            <div id="formMessage" class="alert-enhanced mt-3" style="display:none;"></div>
        </form>
    </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="saveTenantBtn">Save</button>
                <?php if (!$isGuest): ?><button type="button" class="btn btn-secondary" id="cancelFormBtn">Cancel</button><?php endif; ?>
            </div>
            <div id="formMessage"></div>
        </form>
    </div>
    <?php if (!$isGuest): ?><button class="floating-btn" id="addTenantBtn" title="Add Tenant">+</button><?php endif; ?>
</div>
<!-- Confirmation Modal -->
<?php if (!$isGuest): ?>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this tenant?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function() {
    // --- Config ---
    const apiUrl = '/api/crud/';
    let editingId = null;
    let deletingId = null;
    let currentPage = 1;
    let recordsPerPage = 10;
    let filters = {};
    let search = '';
    let isLoading = false;
    let selectedTenantId = null;

    // --- Helpers ---
    function showMessage(msg, isError = false) {
        $('#tableMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
        setTimeout(() => { $('#tableMessage').fadeOut(); }, 3000);
    }
    function showFormMessage(msg, isError = false) {
        $('#formMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
        setTimeout(() => { $('#formMessage').fadeOut(); }, 3000);
    }
    function showContactFormMessage(msg, isError = false) {
        $('#contactFormMessage').removeClass('text-success text-danger').addClass(isError ? 'text-danger' : 'text-success').text(msg).show();
        setTimeout(() => { $('#contactFormMessage').fadeOut(); }, 3000);
    }
    function resetForm() {
        $('#tenantForm')[0].reset();
        $('#tenantId').val('');
        editingId = null;
        $('#formTitle').text('Add Tenant');
        $('#saveTenantBtn').text('Save');
        $('#formMessage').text('');
    }
    function resetContactForm() {
        $('#contactForm')[0].reset();
        $('#contactId').val('');
        $('#contactIsPrimary').prop('checked', false);
        $('#contactFormTitle').text('Add Contact');
        $('#saveContactBtn').text('Save');
        $('#contactFormMessage').text('');
    }
    function getFilters() {
        return {
            name: $('#filterName').val(),
            property: $('#filterProperty').val(),
            flat_office_number: $('#filterFlat').val(),
            email: $('#filterEmail').val(),
            address: $('#filterAddress').val(),
            search: $('#generalSearch').val()
        };
    }
    // --- Table Load ---
    function loadTable(page = 1) {
        if (isLoading) return;
        isLoading = true;
        $('#tenantsTbody').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');
        filters = getFilters();
        search = filters.search;
        let req = {
            table: 'fms_tenants',
            filters: {
                p_id: null,
                p_name: filters.name || null,
                p_property_id: null,
                p_flat_office_number: filters.flat_office_number || null,
                p_email: filters.email || null,
                p_address: filters.address || null,
                p_building_name: filters.property || null,
                p_building_number: null
            },
            page: page,
            per_page: recordsPerPage
        };
        $.post(apiUrl + 'filter', req, function(res) {
            isLoading = false;
            let rows = '';
            if (res && res.length) {
                res.forEach(function(row) {
                    rows += `<tr data-id="${row.id}" class="tenant-row${selectedTenantId==row.id?' table-primary':''}">
                        <td>${row.name || ''}</td>
                        <td>${row.building_name || ''} - ${row.building_number || ''}</td>
                        <td>${row.flat_office_number || ''}</td>
                        <td>${row.email || ''}</td>
                        <td>${row.address || ''}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary editBtn" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger deleteBtn" title="Delete"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>`;
                    // Contacts row (expand area)
                    if (selectedTenantId == row.id) {
                        rows += `<tr class="contacts-row"><td colspan="6">
                            <div id="contactsPanel" class="p-2" style="background:var(--muted);border-radius:var(--radius);">
                                <div class="mb-2"><b>Contacts</b></div>
                                <div id="contactsTableWrap"><table class="table table-sm table-bordered mb-2" id="contactsTable">
                                    <thead><tr><th>Name</th><th>Phone</th><th>Primary</th><th>Actions</th></tr></thead>
                                    <tbody id="contactsTbody"><tr><td colspan="4">Loading...</td></tr></tbody>
                                </table></div>
                                <form id="contactForm" class="row g-2 align-items-end">
                                    <input type="hidden" id="contactId">
                                    <input type="hidden" id="contactTenantId">
                                    <div class="col-md-4"><label class="form-label mb-1">Contact Name <span class="text-danger">*</span></label><input type="text" class="form-control" id="contactName" required></div>
                                    <div class="col-md-4"><label class="form-label mb-1">Phone Number <span class="text-danger">*</span></label><input type="text" class="form-control" id="contactPhone" required></div>
                                    <div class="col-md-2"><label class="form-label mb-1">Primary</label><input type="checkbox" id="contactIsPrimary" class="form-check-input"></div>
                                    <div class="col-md-2 d-flex gap-2"><button type="submit" class="btn btn-primary" id="saveContactBtn">Save</button><button type="button" class="btn btn-secondary" id="cancelContactBtn">Cancel</button></div>
                                    <div class="col-12"><div id="contactFormMessage"></div></div>
                                </form>
                            </div>
                        </td></tr>`;
                    }
                });
            } else {
                rows = '<tr><td colspan="6" class="text-center">No tenants found.</td></tr>';
            }
            $('#tenantsTbody').html(rows);
            if (selectedTenantId) loadContacts(selectedTenantId);
        }).fail(function(xhr) {
            isLoading = false;
            $('#tenantsTbody').html('<tr><td colspan="6" class="text-danger text-center">Error loading data</td></tr>');
        });
    }
    // --- Property Combobox ---
    function loadPropertiesCombo(selectedId) {
        $('#tenantProperty').empty();
        $.post(apiUrl + 'filter', { table: 'fms_properties', filters: {} }, function(res) {
            let opts = '<option value="">Select property...</option>';
            if (res && res.length) {
                res.forEach(function(p) {
                    let label = (p.name || '') + (p.building_number ? ' - ' + p.building_number : '');
                    opts += `<option value="${p.id}"${selectedId == p.id ? ' selected' : ''}>${label}</option>`;
                });
            }
            $('#tenantProperty').html(opts);
        });
    }
    // --- Add/Edit ---
    $('#addTenantBtn').on('click', function() {
        resetForm();
        loadPropertiesCombo();
        $('#tenantFormSection').show();
        $('html,body').animate({scrollTop: $('#tenantFormSection').offset().top-40}, 300);
    });
    $('#cancelFormBtn').on('click', function() {
        resetForm();
        $('#tenantFormSection').hide();
    });
    $('#tenantForm').on('submit', function(e) {
        e.preventDefault();
        let data = {
            name: $('#tenantName').val().trim(),
            property_id: $('#tenantProperty').val(),
            flat_office_number: $('#tenantFlat').val().trim(),
            email: $('#tenantEmail').val().trim(),
            address: $('#tenantAddress').val().trim()
        };
        let id = $('#tenantId').val();
        let url = apiUrl + (id ? 'update' : 'create');
        let req = { table: 'fms_tenants', data: data };
        if (id) req.data.id = id;
        // Email uniqueness check
        $.post(apiUrl + 'filter', { table: 'fms_tenants', filters: { p_email: data.email } }, function(res) {
            if (res && res.length && (!id || res[0].id != id)) {
                showFormMessage('Email must be unique.', true);
                return;
            }
            $.post(url, req, function(resp) {
                showFormMessage('Tenant saved successfully.');
                resetForm();
                loadTable(currentPage);
            }).fail(function(xhr) {
                showFormMessage('Error saving tenant.', true);
            });
        });
    });
    // --- Edit ---
    $('#tenantsTbody').on('click', '.editBtn', function(e) {
        e.stopPropagation();
        let tr = $(this).closest('tr');
        let id = tr.data('id');
        editingId = id;
        $.post(apiUrl + 'filter', { table: 'fms_tenants', filters: { p_id: id } }, function(res) {
            if (res && res.length) {
                let t = res[0];
                $('#tenantId').val(t.id);
                $('#tenantName').val(t.name);
                loadPropertiesCombo(t.property_id);
                $('#tenantFlat').val(t.flat_office_number);
                $('#tenantEmail').val(t.email);
                $('#tenantAddress').val(t.address);
                $('#formTitle').text('Edit Tenant');
                $('#saveTenantBtn').text('Update');
                $('#tenantFormSection').show();
                $('html,body').animate({scrollTop: $('#tenantFormSection').offset().top-40}, 300);
            }
        });
    });
    // --- Delete ---
    let deleteId = null;
    $('#tenantsTbody').on('click', '.deleteBtn', function(e) {
        e.stopPropagation();
        let tr = $(this).closest('tr');
        deleteId = tr.data('id');
        $('#confirmDeleteModal').modal('show');
    });
    $('#confirmDeleteBtn').on('click', function() {
        if (!deleteId) return;
        $.post(apiUrl + 'delete', { table: 'fms_tenants', data: { id: deleteId } }, function(resp) {
            showMessage('Tenant deleted successfully.');
            selectedTenantId = null;
            loadTable(currentPage);
            $('#confirmDeleteModal').modal('hide');
        }).fail(function(xhr) {
            showMessage('Error deleting tenant.', true);
        });
    });
    // --- Row select for contacts ---
    $('#tenantsTbody').on('click', 'tr.tenant-row', function() {
        let id = $(this).data('id');
        if (selectedTenantId == id) {
            selectedTenantId = null;
            loadTable(currentPage);
        } else {
            selectedTenantId = id;
            loadTable(currentPage);
        }
    });
    // --- Contacts CRUD ---
    function loadContacts(tenantId) {
        $('#contactsTbody').html('<tr><td colspan="4">Loading...</td></tr>');
        $.post(apiUrl + 'filter', { table: 'fms_tenant_contacts', filters: { tenant_id: tenantId } }, function(res) {
            let rows = '';
            if (res && res.length) {
                res.forEach(function(c) {
                    rows += `<tr data-id="${c.id}">
                        <td>${c.contact_name || ''}</td>
                        <td>${c.phone_number || ''}</td>
                        <td><input type="checkbox" class="form-check-input setPrimaryContact" ${c.is_primary==1?'checked':''} title="Primary" /></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary editContactBtn" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger deleteContactBtn" title="Delete"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>`;
                });
            } else {
                rows = '<tr><td colspan="4" class="text-center">No contacts found.</td></tr>';
            }
            $('#contactsTbody').html(rows);
            $('#contactTenantId').val(tenantId);
            resetContactForm();
        });
    }
    // Add/Edit Contact
    $('#tenantsTbody').on('submit', '#contactForm', function(e) {
        e.preventDefault();
        let tenantId = $('#contactTenantId').val();
        let data = {
            tenant_id: tenantId,
            contact_name: $('#contactName').val().trim(),
            phone_number: $('#contactPhone').val().trim(),
            is_primary: $('#contactIsPrimary').is(':checked') ? 1 : 0
        };
        let id = $('#contactId').val();
        let url = apiUrl + (id ? 'update' : 'create');
        let req = { table: 'fms_tenant_contacts', data: data };
        if (id) req.data.id = id;
        // Unique phone per tenant
        $.post(apiUrl + 'filter', { table: 'fms_tenant_contacts', filters: { tenant_id: tenantId, phone_number: data.phone_number } }, function(res) {
            if (res && res.length && (!id || res[0].id != id)) {
                showContactFormMessage('Phone number must be unique for this tenant.', true);
                return;
            }
            // Only one primary
            if (data.is_primary) {
                $.post(apiUrl + 'update', { table: 'fms_tenant_contacts', data: { tenant_id: tenantId, is_primary: 0 } }, function() {
                    // Unset all primaries for this tenant before setting new one
                    $.post(url, req, function(resp) {
                        showContactFormMessage('Contact saved successfully.');
                        loadContacts(tenantId);
                    }).fail(function(xhr) {
                        showContactFormMessage('Error saving contact.', true);
                    });
                });
            } else {
                $.post(url, req, function(resp) {
                    showContactFormMessage('Contact saved successfully.');
                    loadContacts(tenantId);
                }).fail(function(xhr) {
                    showContactFormMessage('Error saving contact.', true);
                });
            }
        });
    });
    // Edit Contact
    $('#tenantsTbody').on('click', '.editContactBtn', function() {
        let tr = $(this).closest('tr');
        let id = tr.data('id');
        $.post(apiUrl + 'filter', { table: 'fms_tenant_contacts', filters: { id: id } }, function(res) {
            if (res && res.length) {
                let c = res[0];
                $('#contactId').val(c.id);
                $('#contactTenantId').val(c.tenant_id);
                $('#contactName').val(c.contact_name);
                $('#contactPhone').val(c.phone_number);
                $('#contactIsPrimary').prop('checked', c.is_primary==1);
                $('#contactFormTitle').text('Edit Contact');
                $('#saveContactBtn').text('Update');
            }
        });
    });
    // Delete Contact
    let deleteContactId = null;
    $('#tenantsTbody').on('click', '.deleteContactBtn', function() {
        let tr = $(this).closest('tr');
        deleteContactId = tr.data('id');
        $('#confirmDeleteModal').modal('show');
        $('#confirmDeleteText').text('Are you sure you want to delete this contact?');
    });
    $('#confirmDeleteBtn').on('click', function() {
        if (!deleteContactId) return;
        $.post(apiUrl + 'delete', { table: 'fms_tenant_contacts', data: { id: deleteContactId } }, function(resp) {
            showContactFormMessage('Contact deleted successfully.');
            loadContacts(selectedTenantId);
            $('#confirmDeleteModal').modal('hide');
        }).fail(function(xhr) {
            showContactFormMessage('Error deleting contact.', true);
        });
    });
    // Set Primary Contact
    $('#tenantsTbody').on('change', '.setPrimaryContact', function() {
        let tr = $(this).closest('tr');
        let id = tr.data('id');
        let tenantId = selectedTenantId;
        // Unset all primaries, then set this one
        $.post(apiUrl + 'update', { table: 'fms_tenant_contacts', data: { tenant_id: tenantId, is_primary: 0 } }, function() {
            $.post(apiUrl + 'update', { table: 'fms_tenant_contacts', data: { id: id, is_primary: 1 } }, function() {
                showContactFormMessage('Primary contact updated.');
                loadContacts(tenantId);
            });
        });
    });
    // Cancel Contact
    $('#tenantsTbody').on('click', '#cancelContactBtn', function() {
        resetContactForm();
    });
    // --- Filters & Search ---
    $('#generalSearch, #filterName, #filterProperty, #filterFlat, #filterEmail, #filterAddress').on('input', function() {
        loadTable(1);
    });
    $('#recordsPerPage').on('change', function() {
        recordsPerPage = $(this).val();
        loadTable(1);
    });
    // --- Init ---
    loadTable();
    loadPropertiesCombo();
});
</script>
</body>
</html>
