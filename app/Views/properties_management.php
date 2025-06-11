<!-- Properties Management UI (CRUD, Themed, Responsive) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties Management</title>
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
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Properties Management</h2>
    <div class="card p-2 mb-3">
        <div class="row g-2 align-items-center mb-2">
            <div class="col-md-4">
                <input type="text" id="generalSearch" class="form-control" placeholder="Search properties..." style="font-size:1em;">
            </div>
            <div class="col-md-8 text-end">
                <span id="tableMessage" class="text-success"></span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="propertiesTable" style="font-size:0.98em;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Building Number</th>
                        <th>Address</th>
                        <th style="width:110px;">Actions</th>
                    </tr>
                    <tr class="filter-row">
                        <th><input type="text" class="form-control form-control-sm" id="filterId" placeholder="Filter ID"></th>
                        <th><input type="text" class="form-control form-control-sm" id="filterName" placeholder="Filter name"></th>
                        <th><input type="text" class="form-control form-control-sm" id="filterBuildingNumber" placeholder="Filter building number"></th>
                        <th><input type="text" class="form-control form-control-sm" id="filterAddress" placeholder="Filter address"></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="propertiesTbody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div>
                <label>Show <select id="recordsPerPage" class="form-select form-select-sm d-inline-block" style="width:auto;">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select> entries</label>
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
    <div class="card p-2 form-section" id="propertyFormSection">
        <h5 id="formTitle" style="font-size:1.1rem;">Add Property</h5>
        <form id="propertyForm" autocomplete="off">
            <input type="hidden" id="propertyId">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label mb-1">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="propertyName" required style="font-size:1em;">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1">Building Number</label>
                    <input type="text" class="form-control" id="propertyBuildingNumber" style="font-size:1em;">
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1">Address</label>
                    <input type="text" class="form-control" id="propertyAddress" style="font-size:1em;">
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="savePropertyBtn">Save</button>
                <button type="button" class="btn btn-secondary" id="cancelFormBtn">Cancel</button>
            </div>
            <div id="formMessage"></div>
        </form>
    </div>
    <button class="floating-btn" id="addPropertyBtn" title="Add Property">+</button>
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
        Are you sure you want to delete this property?
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
// ... JS logic for loading, filtering, CRUD for properties will go here ...
</script>
</body>
</html>
