<!-- Properties Management UI (CRUD, Themed, Responsive) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties Management</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: var(--background, #f8f9fa); color: var(--foreground, #333); font-family: var(--font-sans, system-ui); }
        .card, .card-enhanced { background: var(--card, #fff); color: var(--card-foreground, #333); border-radius: var(--radius, 0.5rem); box-shadow: var(--shadow-md, 0 4px 6px rgba(0,0,0,0.1)); border: 1px solid var(--border, #dee2e6); }
        .card-enhanced-header { border-bottom: 1px solid var(--border, #dee2e6); padding-bottom: 1rem; }
        .card-enhanced-title { font-weight: 600; color: var(--foreground, #333); }
        .card-enhanced-content { padding: 1rem; }
        .table-enhanced { width: 100%; margin-bottom: 0; }
        .table-enhanced thead { background: var(--muted, #f8f9fa); color: var(--muted-foreground, #6c757d); }
        .table-enhanced th, .table-enhanced td { padding: 0.3rem 0.5rem; vertical-align: middle; border-top: 1px solid var(--border, #dee2e6); }
        .form-enhanced { background: var(--card, #fff); padding: 1rem; border-radius: var(--radius, 0.5rem); box-shadow: var(--shadow-md, 0 4px 6px rgba(0,0,0,0.1)); border: 1px solid var(--border, #dee2e6); }
        .btn-enhanced { padding: 0.5rem 1rem; border-radius: var(--radius, 0.25rem); font-weight: 500; text-decoration: none; display: inline-block; border: 1px solid transparent; }
        .btn-primary-enhanced { background: var(--primary, #007bff); color: var(--primary-foreground, #fff); border-color: var(--primary, #007bff); }
        .btn-outline-enhanced { background: transparent; color: var(--foreground, #333); border-color: var(--border, #dee2e6); }
        .alert-enhanced { padding: 1rem; border-radius: var(--radius, 0.25rem); margin-top: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .pagination-enhanced .page-link { color: var(--primary, #007bff); }
        .pagination-enhanced .page-item.active .page-link { background: var(--primary, #007bff); border-color: var(--primary, #007bff); }
        .floating-btn {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 1000;
            background: var(--primary, #007bff); color: var(--primary-foreground, #fff); border: none;
            border-radius: 50%; width: 28px; height: 28px; font-size: 1rem; display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-lg, 0 4px 8px rgba(0,0,0,0.15)); cursor: pointer; transition: all 0.3s ease;
        }
        .floating-btn:hover { transform: scale(1.1); }
        .form-section { margin-top: 2rem; max-width: 700px; margin-left: auto; margin-right: auto; }
        .filter-row input { font-size: 0.85em; padding: 0.25rem 0.5rem; }
        .form-control { padding: 0.25rem 0.5rem; font-size: 0.9rem; }
        .form-control-sm { padding: 0.2rem 0.4rem; font-size: 0.8rem; }
        .sort-header { cursor: pointer; user-select: none; position: relative; white-space: nowrap; }
        .sort-header:hover { background-color: var(--muted, #f8f9fa); }
        .sort-icon { margin-left: 5px; opacity: 0.5; }
        .sort-icon.active { opacity: 1; }
        .filter-toggle { cursor: pointer; margin-left: 8px; opacity: 0.6; font-size: 0.8rem; }
        .filter-toggle:hover { opacity: 1; }
        .filter-toggle.active { opacity: 1; color: var(--primary, #007bff); }
        .filter-row { display: none; }
        .filter-row.show { display: table-row; }
        .table-enhanced tbody tr { cursor: pointer; transition: background-color 0.2s; }
        .table-enhanced tbody tr:hover { background-color: var(--muted, #f8f9fa); }
        .table-enhanced tbody tr.selected { background-color: rgba(0,123,255,0.1); }
        .actions-column { position: sticky; right: 0; background: var(--card, #fff); border-left: 1px solid var(--border, #dee2e6); }
        .actions-column.header { background: var(--muted, #f8f9fa); }
        .checkbox-column { width: 30px; text-align: center; padding: 0.2rem 0.3rem !important; }
        .bulk-actions { display: none; margin-bottom: 1rem; }
        .bulk-actions.show { display: block; }
        .row-number { font-size: 0.7em; color: var(--muted-foreground, #6c757d); display: none; }
        .row-number.show { display: block; }
        .form-preview { border: 2px solid var(--primary, #007bff); border-radius: var(--radius, 0.5rem); }
        .export-buttons { margin-left: auto; }
        .table-container { position: relative; max-height: 70vh; overflow-y: auto; }
        .table-enhanced thead th { position: sticky; top: 0; z-index: 10; background: var(--muted, #f8f9fa); }
        .density-toggle { font-size: 0.8rem; margin-left: 10px; }
        .table-dense .table-enhanced th, .table-dense .table-enhanced td { padding: 0.2rem 0.4rem; }
        .table-dense .form-control-sm { padding: 0.1rem 0.3rem; font-size: 0.75rem; }
        .pagination { margin-bottom: 0; }
    </style>
</head>
<body>
<div class="container-fluid py-1" style="max-width:100vw;">
    <h2 class="mb-1" style="font-size:1rem;font-weight:600;">Properties Management</h2>
    <div class="card-enhanced p-1 mb-1">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" id="generalSearch" class="form-control" placeholder="Search properties...">
            </div>
            <div class="col-md-4 text-center">
                <span id="tableMessage" class="text-success"></span>
            </div>
            <div class="col-md-4 text-end">
                <div class="export-buttons">
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="exportData('csv')" title="Export CSV">
                        <i class="fa fa-download"></i> CSV
                    </button>
                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="refreshData()" title="Refresh">
                        <i class="fa fa-refresh"></i>
                    </button>
                    <span class="density-toggle">
                        <label><input type="checkbox" id="densityToggle"> Dense</label>
                    </span>
                    <span class="density-toggle">
                        <label><input type="checkbox" id="rowNumberToggle"> #</label>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bulk-actions" id="bulkActions">
        <div class="alert alert-info p-2">
            <span id="selectedCount">0</span> items selected
            <button class="btn btn-sm btn-danger ms-2" onclick="bulkDelete()">
                <i class="fa fa-trash"></i> Delete Selected
            </button>
            <button class="btn btn-sm btn-secondary ms-1" onclick="clearSelection()">
                <i class="fa fa-times"></i> Clear
            </button>
        </div>
    </div>
    
    <div class="card-enhanced">
        <div class="card-enhanced-content p-0">
            <div class="table-container">
                <table class="table-enhanced" id="propertiesTable">
                    <thead>
                        <tr>
                            <th class="checkbox-column actions-column header">
                                <input type="checkbox" id="selectAll" title="Select All">
                            </th>
                            <th style="width:50px;" class="sort-header" data-sort="id">
                                ID
                                <i class="fa fa-sort sort-icon" data-sort="id"></i>
                                <i class="fa fa-filter filter-toggle" data-filter="id" title="Toggle Filter"></i>
                            </th>
                            <th style="width:30%;" class="sort-header" data-sort="name">
                                Name
                                <i class="fa fa-sort sort-icon" data-sort="name"></i>
                                <i class="fa fa-filter filter-toggle" data-filter="name" title="Toggle Filter"></i>
                            </th>
                            <th style="width:25%;" class="sort-header" data-sort="building_number">
                                Bldg No
                                <i class="fa fa-sort sort-icon" data-sort="building_number"></i>
                                <i class="fa fa-filter filter-toggle" data-filter="building_number" title="Toggle Filter"></i>
                            </th>
                            <th style="width:35%;" class="sort-header" data-sort="address">
                                Address
                                <i class="fa fa-sort sort-icon" data-sort="address"></i>
                                <i class="fa fa-filter filter-toggle" data-filter="address" title="Toggle Filter"></i>
                            </th>
                            <th class="actions-column header" style="width:70px;">Actions</th>
                        </tr>
                        <tr class="filter-row" id="filterRow">
                            <th class="checkbox-column actions-column header"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterId" placeholder="ID"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterName" placeholder="Name"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterBuildingNumber" placeholder="Bldg"></th>
                            <th><input type="text" class="form-control form-control-sm" id="filterAddress" placeholder="Address"></th>
                            <th class="actions-column header"></th>
                        </tr>
                    </thead>
                    <tbody id="propertiesTbody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div>
                    <label class="text-muted">
                        Showing 0 to 0 of 0 entries 
                        <select id="recordsPerPage" class="form-select form-select-sm d-inline-block mx-1" style="width:auto;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select> per page
                    </label>
                </div>
                <nav>
                    <ul class="pagination pagination-enhanced pagination-sm mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="form-enhanced mt-1" id="propertyFormSection">
        <form id="propertyForm" autocomplete="off">
            <input type="hidden" id="propertyId">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label" style="font-size:0.85rem;">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="propertyName" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size:0.85rem;">Bldg No</label>
                    <input type="text" class="form-control" id="propertyBuildingNumber">
                </div>
                <div class="col-md-4">
                    <label class="form-label" style="font-size:0.85rem;">Address</label>
                    <input type="text" class="form-control" id="propertyAddress">
                </div>
            </div>
            <div class="mt-2 d-flex gap-2">
                <button type="submit" class="btn-enhanced btn-primary-enhanced" id="savePropertyBtn">Save Property</button>
                <button type="button" class="btn-enhanced btn-outline-enhanced" id="cancelFormBtn">Cancel</button>
            </div>
            <div id="formMessage" class="alert-enhanced mt-2" style="display:none;"></div>
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
$(document).ready(function() {
    console.log('Properties Management page loaded');
    console.log('jQuery version:', $.fn.jquery);
    
    // Global variables
    let currentPage = 1;
    let recordsPerPage = 10;
    let totalRecords = 0;
    let allData = [];
    let filteredData = [];
    let editingPropertyId = null;
    let currentSort = { field: null, direction: 'asc' };
    let activeFilters = new Set();
    let selectedRows = new Set();
    let hoveredRowData = null;

    // Initialize
    console.log('Initializing properties management...');
    loadProperties();

    // Event Listeners
    $('#addPropertyBtn').click(function() {
        showAddForm();
    });

    $('#cancelFormBtn').click(function() {
        resetForm();
    });

    $('#propertyForm').submit(function(e) {
        e.preventDefault();
        saveProperty();
    });

    $('#recordsPerPage').change(function() {
        recordsPerPage = parseInt($(this).val());
        currentPage = 1;
        renderTable();
        renderPagination();
    });

    $('#generalSearch').on('input', function() {
        applyFilters();
    });

    // Column filters
    $('#filterId, #filterName, #filterBuildingNumber, #filterAddress').on('input', function() {
        applyFilters();
    });

    // Sort functionality
    $('.sort-header').click(function(e) {
        if ($(e.target).hasClass('filter-toggle')) return; // Don't sort when clicking filter icon
        
        const field = $(this).data('sort');
        if (currentSort.field === field) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.field = field;
            currentSort.direction = 'asc';
        }
        
        updateSortIcons();
        sortData();
        renderTable();
    });

    // Filter toggle functionality
    $('.filter-toggle').click(function(e) {
        e.stopPropagation();
        const filterField = $(this).data('filter');
        
        if (activeFilters.has(filterField)) {
            activeFilters.delete(filterField);
            $(this).removeClass('active');
        } else {
            activeFilters.add(filterField);
            $(this).addClass('active');
        }
        
        updateFilterVisibility();
    });

    // Select all functionality
    $('#selectAll').change(function() {
        const isChecked = $(this).is(':checked');
        $('.row-checkbox').prop('checked', isChecked);
        
        if (isChecked) {
            filteredData.forEach(item => selectedRows.add(item.id));
        } else {
            selectedRows.clear();
        }
        
        updateSelectedRowsDisplay();
        updateBulkActions();
    });

    // Density toggle
    $('#densityToggle').change(function() {
        if ($(this).is(':checked')) {
            $('body').addClass('table-dense');
        } else {
            $('body').removeClass('table-dense');
        }
    });

    // Row number toggle
    $('#rowNumberToggle').change(function() {
        if ($(this).is(':checked')) {
            $('.row-number').addClass('show');
        } else {
            $('.row-number').removeClass('show');
        }
    });

    // Row hover functionality
    $(document).on('mouseenter', '#propertiesTbody tr[data-id]', function() {
        const id = $(this).data('id');
        hoveredRowData = allData.find(item => item.id == id);
        if (hoveredRowData) {
            previewRowData(hoveredRowData);
        }
    });

    $(document).on('mouseleave', '#propertiesTbody', function() {
        clearPreview();
    });

    // Row click for selection
    $(document).on('click', '#propertiesTbody tr[data-id]', function(e) {
        if ($(e.target).closest('button, .d-flex').length > 0) return; // Don't select when clicking buttons
        
        const checkbox = $(this).find('.row-checkbox');
        checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
    });

    // Individual checkbox change
    $(document).on('change', '.row-checkbox', function() {
        const id = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        if (isChecked) {
            selectedRows.add(id);
            $(this).closest('tr').addClass('selected');
        } else {
            selectedRows.delete(id);
            $(this).closest('tr').removeClass('selected');
        }
        
        updateSelectAllState();
        updateBulkActions();
    });

    // Update sort icons
    function updateSortIcons() {
        $('.sort-icon').removeClass('active').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        
        if (currentSort.field) {
            const activeIcon = $(`.sort-icon[data-sort="${currentSort.field}"]`);
            activeIcon.addClass('active');
            activeIcon.removeClass('fa-sort');
            activeIcon.addClass(currentSort.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
        }
    }

    // Update filter visibility
    function updateFilterVisibility() {
        if (activeFilters.size > 0) {
            $('#filterRow').addClass('show');
        } else {
            $('#filterRow').removeClass('show');
        }
    }

    // Sort data
    function sortData() {
        if (!currentSort.field) return;
        
        filteredData.sort((a, b) => {
            let aVal = a[currentSort.field] || '';
            let bVal = b[currentSort.field] || '';
            
            // Handle numeric sorting for ID
            if (currentSort.field === 'id') {
                aVal = parseInt(aVal) || 0;
                bVal = parseInt(bVal) || 0;
            } else {
                aVal = String(aVal).toLowerCase();
                bVal = String(bVal).toLowerCase();
            }
            
            if (currentSort.direction === 'asc') {
                return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
            } else {
                return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
            }
        });
    }

    // Preview row data in form
    function previewRowData(data) {
        $('#propertyFormSection').addClass('form-preview');
        $('#propertyName').val(data.name || '');
        $('#propertyBuildingNumber').val(data.building_number || '');
        $('#propertyAddress').val(data.address || '');
        
        // Disable form to show it's preview mode
        $('#propertyForm input').prop('disabled', true);
        $('#savePropertyBtn, #cancelFormBtn').hide();
        
        // Show preview indicator
        if (!$('#previewIndicator').length) {
            $('#propertyFormSection').prepend('<div id="previewIndicator" class="alert alert-info p-2 mb-2"><i class="fa fa-eye"></i> Preview Mode - Hover over rows to preview data</div>');
        }
    }

    function clearPreview() {
        if (!editingPropertyId) { // Only clear if not in edit mode
            $('#propertyFormSection').removeClass('form-preview');
            $('#propertyForm input').prop('disabled', false);
            $('#savePropertyBtn, #cancelFormBtn').show();
            $('#previewIndicator').remove();
            
            if (!editingPropertyId) {
                resetForm();
            }
        }
    }

    // Update select all state
    function updateSelectAllState() {
        const totalCheckboxes = $('.row-checkbox').length;
        const checkedCheckboxes = $('.row-checkbox:checked').length;
        
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0);
    }

    // Update bulk actions visibility
    function updateBulkActions() {
        const selectedCount = selectedRows.size;
        $('#selectedCount').text(selectedCount);
        
        if (selectedCount > 0) {
            $('#bulkActions').addClass('show');
        } else {
            $('#bulkActions').removeClass('show');
        }
    }

    function updateSelectedRowsDisplay() {
        $('.row-checkbox').each(function() {
            const id = $(this).data('id');
            const isSelected = selectedRows.has(id);
            $(this).prop('checked', isSelected);
            $(this).closest('tr').toggleClass('selected', isSelected);
        });
    }

    // Bulk delete functionality
    window.bulkDelete = function() {
        if (selectedRows.size === 0) return;
        
        if (confirm(`Are you sure you want to delete ${selectedRows.size} selected properties?`)) {
            const deletePromises = Array.from(selectedRows).map(id => {
                return $.ajax({
                    url: '/api/crud/delete',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        table: 'properties',
                        data: { id: id }
                    })
                });
            });
            
            Promise.all(deletePromises).then(() => {
                showMessage(`Successfully deleted ${selectedRows.size} properties`, 'success');
                selectedRows.clear();
                loadProperties();
                updateBulkActions();
            }).catch(() => {
                showMessage('Some deletions failed', 'error');
                loadProperties();
            });
        }
    };

    // Clear selection
    window.clearSelection = function() {
        selectedRows.clear();
        $('.row-checkbox').prop('checked', false);
        $('.selected').removeClass('selected');
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        updateBulkActions();
    };

    // Export functionality
    window.exportData = function(format) {
        const dataToExport = filteredData.map(item => ({
            ID: item.id,
            Name: item.name,
            'Building Number': item.building_number,
            Address: item.address,
            'Created At': item.created_at
        }));
        
        if (format === 'csv') {
            downloadCSV(dataToExport, 'properties.csv');
        }
    };

    function downloadCSV(data, filename) {
        if (data.length === 0) return;
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${(row[header] || '').toString().replace(/"/g, '""')}"`).join(','))
        ].join('\n');
        
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // Refresh data
    window.refreshData = function() {
        selectedRows.clear();
        updateBulkActions();
        loadProperties();
    };

    // Load Properties Data
    function loadProperties() {
        console.log('Loading properties...');
        $.ajax({
            url: '/api/crud/filter',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                table: 'properties',
                filters: {}
            }),
            success: function(response) {
                console.log('Properties loaded successfully:', response);
                if (response.success) {
                    allData = response.data || [];
                    filteredData = [...allData];
                    totalRecords = allData.length;
                    sortData(); // Apply any existing sort
                    renderTable();
                    showMessage(`Loaded ${totalRecords} properties`, 'success');
                } else {
                    console.error('Load failed:', response);
                    showMessage('Failed to load properties: ' + (response.message || 'Unknown error'), 'error');
                    allData = [];
                    filteredData = [];
                    renderTable();
                }
            },
            error: function(xhr, status, error) {
                console.error('Load error:', error, xhr.responseText);
                showMessage('Failed to load properties: ' + error, 'error');
                allData = [];
                filteredData = [];
                renderTable();
            }
        });
    }

    // Apply Filters
    function applyFilters() {
        const generalSearch = $('#generalSearch').val().toLowerCase();
        const idFilter = $('#filterId').val().toLowerCase();
        const nameFilter = $('#filterName').val().toLowerCase();
        const buildingNumberFilter = $('#filterBuildingNumber').val().toLowerCase();
        const addressFilter = $('#filterAddress').val().toLowerCase();

        filteredData = allData.filter(function(item) {
            const matchesGeneral = !generalSearch || 
                Object.values(item).some(value => 
                    String(value).toLowerCase().includes(generalSearch)
                );
            
            const matchesId = !idFilter || String(item.id || '').toLowerCase().includes(idFilter);
            const matchesName = !nameFilter || String(item.name || '').toLowerCase().includes(nameFilter);
            const matchesBuildingNumber = !buildingNumberFilter || 
                String(item.building_number || '').toLowerCase().includes(buildingNumberFilter);
            const matchesAddress = !addressFilter || String(item.address || '').toLowerCase().includes(addressFilter);

            return matchesGeneral && matchesId && matchesName && matchesBuildingNumber && matchesAddress;
        });

        // Apply current sort after filtering
        sortData();
        currentPage = 1;
        renderTable();
    }

    // Render Table
    function renderTable() {
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = startIndex + recordsPerPage;
        const pageData = filteredData.slice(startIndex, endIndex);

        let tbody = '';
        if (pageData.length === 0) {
            tbody = '<tr><td colspan="6" class="text-center text-muted py-4">No properties found</td></tr>';
        } else {
            pageData.forEach(function(item, index) {
                const isSelected = selectedRows.has(item.id);
                const rowNumber = startIndex + index + 1;
                tbody += `
                    <tr data-id="${item.id}" class="${isSelected ? 'selected' : ''}">
                        <td class="checkbox-column actions-column">
                            <input type="checkbox" class="row-checkbox" data-id="${item.id}" ${isSelected ? 'checked' : ''}>
                            <div class="row-number">${rowNumber}</div>
                        </td>
                        <td>${item.id || ''}</td>
                        <td>${item.name || ''}</td>
                        <td>${item.building_number || ''}</td>
                        <td>${item.address || ''}</td>
                        <td class="actions-column">
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary" onclick="editProperty(${item.id})" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProperty(${item.id})" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        $('#propertiesTbody').html(tbody);
        updateSelectedRowsDisplay();
        updateSelectAllState();
        renderPagination();
    }

    // Render Pagination
    function renderPagination() {
        const totalPages = Math.ceil(filteredData.length / recordsPerPage);
        const startRecord = filteredData.length === 0 ? 0 : (currentPage - 1) * recordsPerPage + 1;
        const endRecord = Math.min(currentPage * recordsPerPage, filteredData.length);
        
        // Update pagination info
        $('.text-muted').html(`Showing ${startRecord} to ${endRecord} of ${filteredData.length} entries 
            <select id="recordsPerPage" class="form-select form-select-sm d-inline-block mx-1" style="width:auto;">
                <option value="5"${recordsPerPage === 5 ? ' selected' : ''}>5</option>
                <option value="10"${recordsPerPage === 10 ? ' selected' : ''}>10</option>
                <option value="25"${recordsPerPage === 25 ? ' selected' : ''}>25</option>
                <option value="50"${recordsPerPage === 50 ? ' selected' : ''}>50</option>
            </select> per page`);
        
        // Re-bind the change event for the new select element
        $('#recordsPerPage').change(function() {
            recordsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
            renderPagination();
        });

        let pagination = '';

        if (totalPages > 1) {
            // Previous button
            if (currentPage > 1) {
                pagination += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage - 1})">‹</a></li>`;
            } else {
                pagination += `<li class="page-item disabled"><span class="page-link">‹</span></li>`;
            }

            // Page numbers with smart display
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 2);
            
            // Show first page if not in range
            if (startPage > 1) {
                pagination += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1)">1</a></li>`;
                if (startPage > 2) {
                    pagination += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }
            
            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    pagination += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
                } else {
                    pagination += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
                }
            }
            
            // Show last page if not in range
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    pagination += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                pagination += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${totalPages})">${totalPages}</a></li>`;
            }

            // Next button
            if (currentPage < totalPages) {
                pagination += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage + 1})">›</a></li>`;
            } else {
                pagination += `<li class="page-item disabled"><span class="page-link">›</span></li>`;
            }
        }

        $('#pagination').html(pagination);
    }

    // Change Page
    window.changePage = function(page) {
        currentPage = page;
        renderTable();
    };

    // Show Add Form
    function showAddForm() {
        resetForm();
        $('#savePropertyBtn').text('Save Property');
        editingPropertyId = null;
        $('html, body').animate({
            scrollTop: $('#propertyFormSection').offset().top - 50
        }, 500);
    }

    // Edit Property
    window.editProperty = function(id) {
        const property = allData.find(item => item.id == id);
        if (property) {
            editingPropertyId = id;
            $('#propertyId').val(id);
            $('#propertyName').val(property.name || '');
            $('#propertyBuildingNumber').val(property.building_number || '');
            $('#propertyAddress').val(property.address || '');
            $('#savePropertyBtn').text('Update Property');
            
            $('html, body').animate({
                scrollTop: $('#propertyFormSection').offset().top - 50
            }, 500);
        }
    };

    // Save Property
    function saveProperty() {
        const isEdit = editingPropertyId !== null;
        const propertyData = {
            name: $('#propertyName').val().trim(),
            building_number: $('#propertyBuildingNumber').val().trim(),
            address: $('#propertyAddress').val().trim()
        };

        if (isEdit) {
            propertyData.id = editingPropertyId;
        }

        if (!propertyData.name) {
            showFormMessage('Property name is required', 'error');
            return;
        }

        const endpoint = isEdit ? '/api/crud/update' : '/api/crud/create';
        
        $.ajax({
            url: endpoint,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                table: 'properties',
                data: propertyData
            }),
            success: function(response) {
                console.log('Response:', response); // Debug log
                if (response.success) {
                    showFormMessage(isEdit ? 'Property updated successfully' : 'Property added successfully', 'success');
                    setTimeout(() => {
                        resetForm();
                        loadProperties();
                    }, 1500);
                } else {
                    showFormMessage('Failed to save property: ' + (response.message || 'Unknown error'), 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Save error:', error);
                console.error('Response:', xhr.responseText); // Debug log
                showFormMessage('Failed to save property: ' + error, 'error');
            }
        });
    }

    // Delete Property
    window.deleteProperty = function(id) {
        const property = allData.find(item => item.id == id);
        if (property) {
            $('#confirmDeleteModal').modal('show');
            
            $('#confirmDeleteBtn').off('click').on('click', function() {
                $.ajax({
                    url: '/api/crud/delete',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        table: 'properties',
                        data: { id: id }
                    }),
                    success: function(response) {
                        $('#confirmDeleteModal').modal('hide');
                        if (response.success) {
                            showMessage('Property deleted successfully', 'success');
                            loadProperties();
                        } else {
                            showMessage('Failed to delete property: ' + (response.message || 'Unknown error'), 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#confirmDeleteModal').modal('hide');
                        console.error('Delete error:', error);
                        showMessage('Failed to delete property: ' + error, 'error');
                    }
                });
            });
        }
    };

    // Reset Form
    function resetForm() {
        $('#propertyForm')[0].reset();
        $('#propertyId').val('');
        editingPropertyId = null;
        $('#formTitle').text('Add Property');
        $('#savePropertyBtn').text('Save Property');
        hideFormMessage();
    }

    // Show Messages
    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'text-success' : 'text-danger';
        $('#tableMessage').removeClass('text-success text-danger').addClass(alertClass).text(message);
        setTimeout(() => $('#tableMessage').text(''), 5000);
    }

    function showFormMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#formMessage').removeClass('alert-success alert-danger').addClass(alertClass).text(message).show();
    }

    function hideFormMessage() {
        $('#formMessage').hide();
    }
});
</script>
</body>
</html>
