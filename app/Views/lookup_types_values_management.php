<!-- Master-Detail CRUD for Lookup Types & Values (Themed, Responsive) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lookup Types & Values Management</title>
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
        .form-section { margin-top: 2rem; max-width: 700px; margin-left: auto; margin-right: auto; }
        .alert { margin-top: 1rem; }
        .filter-row input { font-size: 0.95em; }
        .pagination { margin-bottom: 0; }
        .nested-table-section { margin-top: 2.5rem; }
        .switch {
            position: relative; display: inline-block; width: 38px; height: 22px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background: var(--muted); transition: .2s; border-radius: 22px;
        }
        .slider:before {
            position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px;
            background: var(--primary); transition: .2s; border-radius: 50%;
        }
        input:checked + .slider { background: var(--primary); }
        input:checked + .slider:before { transform: translateX(16px); background: var(--primary-foreground); }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Lookup Types & Values Management</h2>
    <div class="card p-2 mb-3">
        <div class="row g-2 align-items-center mb-2">
            <div class="col-md-4">
                <input type="text" id="generalSearchType" class="form-control" placeholder="Search types..." style="font-size:1em;">
            </div>
            <div class="col-md-8 text-end">
                <span id="typeTableMessage" class="text-success"></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="lookupTypesTable" style="font-size:0.98em;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                    <tr class="filter-row">
                        <th><input type="text" class="form-control form-control-sm" id="filterTypeId" placeholder="Filter ID"></th>
                        <th><input type="text" class="form-control form-control-sm" id="filterTypeName" placeholder="Filter name"></th>
                        <th><input type="text" class="form-control form-control-sm" id="filterTypeCode" placeholder="Filter code"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="lookupTypesTbody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div>
                <label>Show <select id="recordsPerPageType" class="form-select form-select-sm d-inline-block" style="width:auto;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select> entries</label>
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginationType"></ul>
            </nav>
        </div>
    </div>
    <div class="card p-2 form-section" id="lookupTypeFormSection">
        <h5 id="formTitleType" style="font-size:1.1rem;">Add Lookup Type</h5>
        <form id="lookupTypeForm" autocomplete="off">
            <input type="hidden" id="lookupTypeId">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label mb-1">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lookupTypeName" required style="font-size:1em;">
                </div>
                <div class="col-md-6">
                    <label class="form-label mb-1">Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lookupTypeCode" required style="font-size:1em;">
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="saveLookupTypeBtn">Save</button>
                <button type="button" class="btn btn-secondary" id="cancelTypeFormBtn">Cancel</button>
            </div>
            <div id="formMessageType"></div>
        </form>
    </div>
    <button class="floating-btn" id="addLookupTypeBtn" title="Add Lookup Type">+</button>

    <!-- Nested Lookup Values Section -->
    <div class="nested-table-section" id="lookupValuesSection" style="display:none;">
        <div class="card p-2 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" id="selectedTypeTitle">Lookup Values</h5>
                <span id="valueTableMessage" class="text-success"></span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="lookupValuesTable" style="font-size:0.98em;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Is Active</th>
                            <th>Sort Order</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                        <tr class="filter-row">
                            <th><input type="text" class="form-control form-control-sm" id="filterValueId" placeholder="Filter ID"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterValueName" placeholder="Filter name"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterValueCode" placeholder="Filter code"></th>
                            <th></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterValueSortOrder" placeholder="Filter sort"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="lookupValuesTbody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <label>Show <select id="recordsPerPageValue" class="form-select form-select-sm d-inline-block" style="width:auto;">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select> entries</label>
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationValue"></ul>
                </nav>
            </div>
        </div>
        <div class="card p-2 form-section" id="lookupValueFormSection">
            <h5 id="formTitleValue" style="font-size:1.1rem;">Add Lookup Value</h5>
            <form id="lookupValueForm" autocomplete="off">
                <input type="hidden" id="lookupValueId">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lookupValueName" required style="font-size:1em;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">Code</label>
                        <input type="text" class="form-control" id="lookupValueCode" style="font-size:1em;">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1">Sort Order</label>
                        <input type="number" class="form-control" id="lookupValueSortOrder" value="0" style="font-size:1em;">
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <label class="form-label mb-1 me-2">Is Active</label>
                        <label class="switch mb-0">
                            <input type="checkbox" id="lookupValueIsActive" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="saveLookupValueBtn">Save</button>
                    <button type="button" class="btn btn-secondary" id="cancelValueFormBtn">Cancel</button>
                </div>
                <div id="formMessageValue"></div>
            </form>
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
      <div class="modal-body" id="confirmDeleteText">
        Are you sure you want to delete this record?
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
<script>
// --- Master-Detail AJAX CRUD logic for Lookup Types & Values ---
// This is a placeholder for your JS logic. You should:
// 1. Use AJAX to load, filter, paginate, add, edit, delete lookup types (parent table)
// 2. On row select, load child lookup values for that type (AJAX, filter, paginate, add, edit, delete)
// 3. Use inline success/error messages, confirmation dialogs, and theme classes
// 4. Use the correct API endpoints for stored-procedure-based CRUD
// 5. Use minimal padding, keep all UI responsive and clean
//
// Example: $(document).ready(function() { ... });
</script>
</body>
</html>
