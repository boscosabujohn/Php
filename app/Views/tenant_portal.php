<!-- Enhanced Tenant Portal UI for Service Requests -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Portal - Service Requests</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        body { 
            background: var(--background); 
            color: var(--foreground); 
            font-family: var(--font-sans); 
            padding-top: 2rem;
        }
        
        .portal-header { 
            background: linear-gradient(135deg, var(--primary) 0%, hsl(from var(--primary) h s calc(l - 15%)) 100%);
            color: var(--primary-foreground); 
            border-radius: calc(var(--radius) * 2); 
            box-shadow: var(--shadow-xl); 
            padding: 2rem; 
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .portal-title { 
            font-size: 2rem; 
            font-weight: 800; 
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }
        
        .portal-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .tenant-info {
            background: var(--muted);
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        
        .file-drop { 
            border: 2px dashed var(--border); 
            border-radius: var(--radius); 
            padding: 1.5rem; 
            cursor: pointer; 
            text-align: center;
            background: var(--muted);
            transition: all 0.2s ease;
        }
        
        .file-drop:hover {
            border-color: var(--primary);
            background: var(--accent);
        }
        
        .progress { 
            height: 1.2rem;
            border-radius: var(--radius);
        }
        
        .navbar-portal {
            background: var(--card);
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .logout-btn {
            background: var(--destructive);
            color: var(--destructive-foreground);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .logout-btn:hover {
            background: hsl(from var(--destructive) h s calc(l - 10%));
            color: var(--destructive-foreground);
        }
        
        @media (max-width: 768px) {
            .portal-header {
                padding: 1.5rem 1rem;
            }
            
            .portal-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="max-width: 1200px;">
        <!-- Navigation Bar -->
        <div class="navbar-portal d-flex justify-content-between align-items-center">
            <div>
                <strong>🏠 Tenant Portal</strong>
                <?php if (isset($tenant_name)): ?>
                    <span class="text-muted">- Welcome, <?= esc($tenant_name) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <?php if (isset($is_guest) && $is_guest): ?>
                    <a href="/tenant/logout" class="logout-btn">Logout</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Portal Header -->
        <div class="portal-header">
            <div class="portal-title">Maintenance Request Portal</div>
            <div class="portal-subtitle">Submit and track your maintenance requests online</div>
            
            <?php if (isset($tenant_name, $flat_office_number)): ?>
                <div class="tenant-info">
                    <strong><?= esc($tenant_name) ?></strong> 
                    <?php if ($flat_office_number): ?>
                        • Unit: <?= esc($flat_office_number) ?>
                    <?php endif; ?>
                    <?php if (isset($tenant_email)): ?>
                        • <?= esc($tenant_email) ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- New Maintenance Request Form -->
        <div class="card-enhanced mb-4">
            <div class="card-enhanced-header">
                <h5 class="card-enhanced-title mb-0">🔧 Submit New Maintenance Request</h5>
            </div>
            <div class="card-enhanced-content">
                <form id="requestForm" class="form-enhanced" autocomplete="off">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Property <span class="text-danger">*</span></label>
                            <select id="propertyId" class="form-control" required></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select id="categoryId" class="form-control" required></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <select id="priorityId" class="form-control" required></select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea id="description" class="form-control" rows="3" required placeholder="Please describe the maintenance issue in detail..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Attachments</label>
                            <div class="file-drop" id="fileDrop">
                                📁 Drag & drop files here or click to select photos/documents
                            </div>
                            <input type="file" id="attachments" multiple accept="image/*,.pdf,.doc,.docx" style="display:none;">
                            <div id="fileList" class="mt-2"></div>
                            <div class="progress mt-2" id="uploadProgress" style="display:none;">
                                <div class="progress-bar" role="progressbar" style="width:0%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex gap-3">
                        <button type="submit" class="btn-enhanced btn-primary-enhanced" id="saveRequestBtn">
                            📤 Submit Request
                        </button>
                        <button type="button" class="btn-enhanced btn-outline-enhanced" onclick="document.getElementById('requestForm').reset();">
                            🗑️ Clear Form
                        </button>
                    </div>
                    
                    <div id="formMessage" class="mt-3"></div>
                </form>
            </div>
        </div>
        <!-- My Service Requests Table -->
        <div class="card-enhanced">
            <div class="card-enhanced-header">
                <h5 class="card-enhanced-title mb-0">📋 My Service Requests</h5>
            </div>
            <div class="card-enhanced-content p-0">
                <!-- Search and Filters -->
                <div class="p-3 border-bottom">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <input type="text" id="generalSearch" class="form-control" placeholder="🔍 Search requests..." style="font-size:1em;">
                        </div>
                        <div class="col-md-6 text-end">
                            <span id="tableMessage" class="text-success"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Requests Table -->
                <div class="table-responsive">
                    <table class="table-enhanced" id="requestsTable">
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
                                <th><select class="form-control form-control-sm" id="filterProperty"></select></th>
                                <th><select class="form-control form-control-sm" id="filterCategory"></select></th>
                                <th><select class="form-control form-control-sm" id="filterPriority"></select></th>
                                <th><select class="form-control form-control-sm" id="filterStatus"></select></th>
                                <th></th><th></th><th></th><th></th><th></th>
                            </tr>
                        </thead>
                        <tbody id="requestsTbody">
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Loading your requests...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
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
            const alertHtml = `
                <div class="alert alert-${type} alert-enhanced alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('#formMessage').html(alertHtml);
            
            // Auto-dismiss success messages
            if (type === 'success') {
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
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
