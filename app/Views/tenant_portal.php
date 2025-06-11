<!-- Tenant Portal UI for Service Requests -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tenant Portal - Service Requests</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .dashboard-card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); padding: 2rem 1.5rem; max-width: 800px; margin: 5vh auto; text-align: center; }
        .dashboard-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.2rem; }
        .form-section { background: var(--card); padding: 1.5rem; border-radius: var(--radius); box-shadow: var(--shadow-md); margin-bottom: 1.5rem; }
        .table th, .table td { vertical-align: middle; }
        .file-drop { border: 2px dashed var(--border); border-radius: var(--radius); padding: 1rem; cursor: pointer; text-align: center; }
        .progress { height: 1rem; }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <div class="dashboard-title">Welcome to the Tenant Portal</div>
        <p>Here you can create and manage your service requests.</p>
    </div>

    <div class="container-fluid py-4" style="max-width:100vw;">
        <h2 class="mb-4" style="font-size:1.4rem;">My Service Requests</h2>
        <div class="card p-2 form-section" id="requestFormSection">
            <h5 style="font-size:1.1rem;">New Maintenance Request</h5>
            <form id="requestForm" autocomplete="off">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Property <span class="text-danger">*</span></label>
                        <select id="propertyId" class="form-select" required></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">Category <span class="text-danger">*</span></label>
                        <select id="categoryId" class="form-select" required></select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-1">Priority <span class="text-danger">*</span></label>
                        <select id="priorityId" class="form-select" required></select>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md-12">
                        <label class="form-label mb-1">Description <span class="text-danger">*</span></label>
                        <textarea id="description" class="form-control" rows="2" required></textarea>
                    </div>
                </div>
                <div class="row g-2 mt-2">
                    <div class="col-md-12">
                        <label class="form-label mb-1">Attachments</label>
                        <div class="file-drop" id="fileDrop">Drag & drop files here or click to select</div>
                        <input type="file" id="attachments" multiple style="display:none;">
                        <div id="fileList"></div>
                        <div class="progress" id="uploadProgress" style="display:none;"><div class="progress-bar" role="progressbar" style="width:0%"></div></div>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="saveRequestBtn">Submit Request</button>
                </div>
                <div id="formMessage"></div>
            </form>
        </div>
        <div class="card p-2 mt-4">
            <div class="row g-2 align-items-center mb-2">
                <div class="col-md-4">
                    <input type="text" id="generalSearch" class="form-control" placeholder="Search requests..." style="font-size:1em;">
                </div>
                <div class="col-md-8 text-end">
                    <span id="tableMessage" class="text-success"></span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="requestsTable" style="font-size:0.98em;">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Attachments</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                        <tr class="filter-row">
                            <th><select class="form-select form-select-sm" id="filterProperty"></select></th>
                            <th><select class="form-select form-select-sm" id="filterCategory"></select></th>
                            <th><select class="form-select form-select-sm" id="filterPriority"></select></th>
                            <th><select class="form-select form-select-sm" id="filterStatus"></select></th>
                            <th></th><th></th><th></th><th></th><th></th>
                        </tr>
                    </thead>
                    <tbody id="requestsTbody">
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // JavaScript logic for handling service request form submission, file uploads, and table display
        $(document).ready(function() {
            // Initialize Select2 for all select elements
            $('select').select2({ minimumResultsForSearch: Infinity });

            // Load properties, categories, and priorities for the dropdowns
            loadProperties();
            loadCategories();
            loadPriorities();

            // Handle form submission
            $('#requestForm').on('submit', function(e) {
                e.preventDefault();
                submitRequestForm();
            });

            // General search input handler
            $('#generalSearch').on('input', function() {
                loadRequestsTable();
            });

            // Records per page change handler
            $('#recordsPerPage').on('change', function() {
                loadRequestsTable();
            });

            // File drop area click handler
            $('#fileDrop').on('click', function() {
                $('#attachments').click();
            });

            // File input change handler
            $('#attachments').on('change', function() {
                handleFileSelect(this.files);
            });

            // Load requests table on page load
            loadRequestsTable();
        });

        function loadProperties() {
            // AJAX request to load properties
            $.ajax({
                url: '/api/properties',
                method: 'GET',
                success: function(data) {
                    let options = '<option value="">Select Property</option>';
                    data.forEach(function(property) {
                        options += `<option value="${property.id}">${property.name}</option>`;
                    });
                    $('#propertyId').html(options).prop('disabled', false);
                    $('#propertyId').select2('val', '');
                },
                error: function() {
                    showAlert('Error loading properties.', 'danger');
                }
            });
        }

        function loadCategories() {
            // AJAX request to load categories
            $.ajax({
                url: '/api/categories',
                method: 'GET',
                success: function(data) {
                    let options = '<option value="">Select Category</option>';
                    data.forEach(function(category) {
                        options += `<option value="${category.id}">${category.name}</option>`;
                    });
                    $('#categoryId').html(options).prop('disabled', false);
                    $('#categoryId').select2('val', '');
                },
                error: function() {
                    showAlert('Error loading categories.', 'danger');
                }
            });
        }

        function loadPriorities() {
            // AJAX request to load priorities
            $.ajax({
                url: '/api/priorities',
                method: 'GET',
                success: function(data) {
                    let options = '<option value="">Select Priority</option>';
                    data.forEach(function(priority) {
                        options += `<option value="${priority.id}">${priority.name}</option>`;
                    });
                    $('#priorityId').html(options).prop('disabled', false);
                    $('#priorityId').select2('val', '');
                },
                error: function() {
                    showAlert('Error loading priorities.', 'danger');
                }
            });
        }

        function submitRequestForm() {
            // Gather form data
            let formData = new FormData($('#requestForm')[0]);

            // AJAX request to submit the form
            $.ajax({
                url: '/api/requests',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#formMessage').html('<div class="alert alert-success">Request submitted successfully!</div>');
                    $('#requestForm')[0].reset();
                    $('#fileList').html('');
                    loadRequestsTable();
                },
                error: function(jqXHR) {
                    let errorMessage = 'Error submitting request.';
                    if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                        errorMessage = jqXHR.responseJSON.message;
                    }
                    $('#formMessage').html(`<div class="alert alert-danger">${errorMessage}</div>`);
                }
            });
        }

        function loadRequestsTable() {
            // Get filter values
            let propertyId = $('#filterProperty').val();
            let categoryId = $('#filterCategory').val();
            let priorityId = $('#filterPriority').val();
            let statusId = $('#filterStatus').val();
            let searchQuery = $('#generalSearch').val();
            let recordsPerPage = $('#recordsPerPage').val();

            // AJAX request to load requests
            $.ajax({
                url: '/api/requests',
                method: 'GET',
                data: {
                    propertyId: propertyId,
                    categoryId: categoryId,
                    priorityId: priorityId,
                    statusId: statusId,
                    search: searchQuery,
                    limit: recordsPerPage
                },
                success: function(data) {
                    let rows = '';
                    data.requests.forEach(function(request) {
                        rows += `
                            <tr>
                                <td>${request.property_name}</td>
                                <td>${request.category_name}</td>
                                <td>${request.priority_name}</td>
                                <td>${request.status_name}</td>
                                <td>${request.description}</td>
                                <td>${request.attachments.map(att => `<a href="${att.url}" target="_blank">View</a>`).join(', ')}</td>
                                <td>${formatDate(request.created_at)}</td>
                                <td>${formatDate(request.updated_at)}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editRequest(${request.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteRequest(${request.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#requestsTbody').html(rows);
                    updatePagination(data.total, recordsPerPage);
                    $('#tableMessage').text(`Showing ${data.requests.length} of ${data.total} requests`);
                },
                error: function() {
                    $('#requestsTbody').html('');
                    $('#tableMessage').text('Error loading requests').addClass('text-danger');
                }
            });
        }

        function updatePagination(totalRecords, recordsPerPage) {
            let totalPages = Math.ceil(totalRecords / recordsPerPage);
            let paginationHtml = '';

            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
            }

            $('#pagination').html(paginationHtml);
        }

        function changePage(pageNumber) {
            // TODO: Implement page change logic
        }

        function handleFileSelect(files) {
            $('#fileList').html('');
            $('#uploadProgress').hide();

            if (files.length > 0) {
                let fileNames = [];
                for (let i = 0; i < files.length; i++) {
                    fileNames.push(files[i].name);
                }
                $('#fileList').html('<div class="text-success">' + fileNames.join(', ') + '</div>');
            }
        }

        function formatDate(dateString) {
            let options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            return new Date(dateString).toLocaleString('en-US', options);
        }

        function showAlert(message, type) {
            // Generic alert function
            $('#formMessage').html(`<div class="alert alert-${type}">${message}</div>`);
        }

        function editRequest(requestId) {
            // TODO: Implement request edit logic
        }

        function deleteRequest(requestId) {
            // TODO: Implement request delete logic
        }
    </script>
</body>
</html>
