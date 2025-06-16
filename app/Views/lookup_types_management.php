<!-- Lookup Types Management UI (CRUD, Themed, Responsive) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lookup Types Management</title>
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
        .form-section { margin-top: 2rem; max-width: 600px; margin-left: auto; margin-right: auto; }
        .alert { margin-top: 1rem; }
        .filter-row input { font-size: 0.95em; }
        .pagination { margin-bottom: 0; }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Lookup Types Management</h2>
    <div class="card-enhanced p-3 mb-4">
        <div class="card-enhanced-header mb-3">
            <h5 class="card-enhanced-title mb-0">Search & Filter Lookup Types</h5>
        </div>
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <input type="text" id="generalSearch" class="form-control" placeholder="Search lookup types...">
            </div>
            <div class="col-md-6 text-end">
                <span id="tableMessage" class="text-success"></span>
            </div>
        </div>
    </div>
    
    <div class="card-enhanced">
        <div class="card-enhanced-header">
            <h5 class="card-enhanced-title mb-0">Lookup Types Directory</h5>
        </div>
        <div class="card-enhanced-content p-0">
            <div class="table-responsive">
                <table class="table-enhanced" id="lookupTypesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                        <tr class="filter-row">
                            <th><input type="text" class="form-control form-control-sm" id="filterId" placeholder="Filter ID"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterName" placeholder="Filter name"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterCode" placeholder="Filter code"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="lookupTypesTbody">
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
    <div class="card p-2 form-section" id="lookupTypeFormSection">
        <h5 id="formTitle" style="font-size:1.1rem;">Add Lookup Type</h5>
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
                <button type="button" class="btn btn-secondary" id="cancelFormBtn">Cancel</button>
            </div>
            <div id="formMessage"></div>
        </form>
    </div>
    <button class="floating-btn" id="addLookupTypeBtn" title="Add Lookup Type">+</button>
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
        Are you sure you want to delete this lookup type?
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
// ... JS logic for loading, filtering, CRUD for lookup types will go here ...
</script>
</body>
</html>
