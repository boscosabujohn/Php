<!-- Workflow Escalations Management -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workflow Escalations</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .alert { border-radius: var(--radius); }
        .btn-group .btn { margin-right: 0.5rem; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <h2 class="mb-4">Workflow Escalations Management</h2>
        
        <div class="card p-4 mb-4">
            <h5>Escalation Controls</h5>
            <p class="text-muted">Manage and trigger workflow escalations for overdue maintenance requests.</p>
            
            <div class="btn-group mb-3">
                <button class="btn btn-primary" id="runEscalationsBtn">
                    <i class="bi bi-play-circle"></i> Run Escalations
                </button>
                <button class="btn btn-outline-secondary" id="viewEscalatedBtn">
                    <i class="bi bi-list"></i> View Escalated Requests
                </button>
                <button class="btn btn-outline-info" id="viewDelayedBtn">
                    <i class="bi bi-clock"></i> View Delayed Requests
                </button>
            </div>
            
            <div id="escalationResults" class="mt-3"></div>
        </div>

        <div class="card p-4">
            <h5>Escalation History</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Property</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Escalated At</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="escalationHistoryTbody">
                        <tr>
                            <td colspan="8" class="text-center text-muted">Loading escalation history...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script>
        const apiUrl = '/api/crud/';

        function showMessage(message, type = 'info') {
            $('#escalationResults').html(`<div class="alert alert-${type}">${message}</div>`);
            setTimeout(() => $('#escalationResults').fadeOut(), 5000);
        }

        $('#runEscalationsBtn').on('click', function() {
            $(this).prop('disabled', true).html('<i class="bi bi-hourglass"></i> Processing...');
            
            $.post('/workflow/escalations', {}, function(response) {
                if (response.status === 'ok') {
                    showMessage('✅ ' + response.message, 'success');
                    loadEscalationHistory();
                } else {
                    showMessage('❌ Error: ' + (response.message || 'Unknown error'), 'danger');
                }
            }).fail(function() {
                showMessage('❌ Failed to run escalations', 'danger');
            }).always(function() {
                $('#runEscalationsBtn').prop('disabled', false).html('<i class="bi bi-play-circle"></i> Run Escalations');
            });
        });

        $('#viewEscalatedBtn').on('click', function() {
            // Filter for escalated requests
            loadEscalationHistory({ status: 'Escalated' });
        });

        $('#viewDelayedBtn').on('click', function() {
            // Filter for delayed requests
            let today = new Date().toISOString().slice(0, 10);
            loadEscalationHistory({ due_date_lt: today, status_not: 'Completed' });
        });

        function loadEscalationHistory(filters = {}) {
            $('#escalationHistoryTbody').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
            
            $.post(apiUrl + 'filter', {
                table: 'fms_maintenance_requests',
                filters: filters
            }, function(response) {
                let rows = '';
                if (response && response.length > 0) {
                    response.forEach(function(request) {
                        rows += `
                            <tr>
                                <td>${request.id}</td>
                                <td>${request.property_name || '-'}</td>
                                <td>${request.category_name || '-'}</td>
                                <td><span class="badge bg-${request.status === 'Escalated' ? 'danger' : 'warning'}">${request.status || '-'}</span></td>
                                <td>${request.due_date || '-'}</td>
                                <td>${request.escalated_at || '-'}</td>
                                <td>${request.assigned_to_name || 'Unassigned'}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary viewBtn" data-id="${request.id}">View</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = '<tr><td colspan="8" class="text-center text-muted">No escalated requests found</td></tr>';
                }
                $('#escalationHistoryTbody').html(rows);
            }).fail(function() {
                $('#escalationHistoryTbody').html('<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>');
            });
        }

        // Load escalation history on page load
        $(document).ready(function() {
            loadEscalationHistory();
        });

        // Handle view button clicks
        $('#escalationHistoryTbody').on('click', '.viewBtn', function() {
            let requestId = $(this).data('id');
            window.location.href = `/assignment/${requestId}`;
        });
    </script>
</body>
</html>
