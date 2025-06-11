<!-- Supervisor Dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        .dashboard-metrics { display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .metric-box { flex: 1 1 200px; min-width: 200px; background: var(--muted); color: var(--muted-foreground); border-radius: var(--radius); padding: 1.5rem 1rem; text-align: center; box-shadow: var(--shadow-sm); }
        .metric-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .metric-value { font-size: 2.2rem; font-weight: 700; }
        .table thead { background: var(--muted); color: var(--muted-foreground); }
        .table th, .table td { vertical-align: middle; }
        .action-btns button { margin-right: 0.3rem; }
        .filter-bar { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; align-items: center; }
        .chart-container { background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow-sm); padding: 1rem; margin-bottom: 2rem; }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="max-width:100vw;">
    <h2 class="mb-4" style="font-size:1.4rem;">Supervisor Dashboard</h2>
    <div class="filter-bar">
        <label>From: <input type="date" id="filterFrom" class="form-control form-control-sm d-inline-block" style="width:auto;"></label>
        <label>To: <input type="date" id="filterTo" class="form-control form-control-sm d-inline-block" style="width:auto;"></label>
        <label>Status:
            <select id="filterStatus" class="form-select form-select-sm d-inline-block" style="width:auto;">
                <option value="">All</option>
                <option value="Open">Open</option>
                <option value="Completed">Completed</option>
                <option value="Escalated">Escalated</option>
            </select>
        </label>
        <label>Property:
            <select id="filterProperty" class="form-select form-select-sm d-inline-block" style="width:auto;"></select>
        </label>
        <button class="btn btn-sm btn-primary" id="applyFiltersBtn">Apply</button>
        <button class="btn btn-sm btn-secondary" id="resetFiltersBtn">Reset</button>
    </div>
    <div class="dashboard-metrics">
        <div class="metric-box">
            <div class="metric-title">Total Requests</div>
            <div class="metric-value" id="totalRequests">0</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Open Requests</div>
            <div class="metric-value" id="openRequests">0</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Completed</div>
            <div class="metric-value text-success" id="completedRequests">0</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Escalated</div>
            <div class="metric-value text-danger" id="escalatedRequests">0</div>
        </div>
        <div class="metric-box">
            <div class="metric-title">Pending Review</div>
            <div class="metric-value text-warning" id="pendingReview">0</div>
        </div>
    </div>
    <div class="chart-container mb-4">
        <canvas id="requestsTrendChart" height="80"></canvas>
    </div>
    <div class="card p-2 mb-4">
        <h5 class="mb-3">Requests Pending Review</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="reviewTable" style="font-size:0.98em;">
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Property</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="reviewTbody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="card p-2 mb-4">
        <h5 class="mb-3">All Maintenance Requests</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="requestsTable" style="font-size:0.98em;">
                <thead>
                    <tr>
                        <th>Request #</th>
                        <th>Property</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="requestsTbody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
const apiUrl = '/api/crud/';
let properties = [], categories = [], users = [], allRequests = [];
function getName(arr, id) { let o = arr.find(x => x.id == id); return o ? o.name : ''; }
function getUserName(id) { let u = users.find(x => x.id == id); return u ? u.name : ''; }
function loadLookups() {
    $.post(apiUrl + 'filter', { table: 'fms_properties', filters: {} }, function(res) {
        properties = res || [];
        let opts = '<option value="">All</option>';
        properties.forEach(p => opts += `<option value="${p.id}">${p.name}</option>`);
        $('#filterProperty').html(opts);
    });
    $.post(apiUrl + 'filter', { table: 'fms_lookup_values', filters: { type: 'Category' } }, function(res) { categories = res || []; });
    $.post(apiUrl + 'filter', { table: 'fms_users', filters: {} }, function(res) { users = res || []; });
}
function applyFilters() {
    let from = $('#filterFrom').val();
    let to = $('#filterTo').val();
    let status = $('#filterStatus').val();
    let property = $('#filterProperty').val();
    let filtered = allRequests.filter(r => {
        let ok = true;
        if (from && r.created_at < from) ok = false;
        if (to && r.created_at > to) ok = false;
        if (status && r.status !== status) ok = false;
        if (property && r.property_id != property) ok = false;
        return ok;
    });
    renderDashboard(filtered);
}
function resetFilters() {
    $('#filterFrom').val('');
    $('#filterTo').val('');
    $('#filterStatus').val('');
    $('#filterProperty').val('');
    renderDashboard(allRequests);
}
function renderDashboard(requests) {
    $('#totalRequests').text(requests.length);
    $('#openRequests').text(requests.filter(r => r.status !== 'Completed').length);
    $('#completedRequests').text(requests.filter(r => r.status === 'Completed').length);
    $('#escalatedRequests').text(requests.filter(r => r.status === 'Escalated').length);
    let pendingReview = requests.filter(r => r.status === 'Completed' && !r.reviewed_by_supervisor);
    $('#pendingReview').text(pendingReview.length);
    // Pending review table
    let reviewRows = '';
    pendingReview.forEach(function(r) {
        reviewRows += `<tr data-id="${r.id}">
            <td>${r.id}</td>
            <td>${getName(properties, r.property_id)}</td>
            <td>${getName(categories, r.category_id)}</td>
            <td>${r.status || ''}</td>
            <td>${getUserName(r.assigned_to)}</td>
            <td>${r.due_date || ''}</td>
            <td class="action-btns">
                <button class="btn btn-sm btn-outline-primary reviewBtn">Review</button>
            </td>
        </tr>`;
    });
    $('#reviewTbody').html(reviewRows || '<tr><td colspan="7" class="text-center">No requests pending review.</td></tr>');
    // All requests table
    let rows = '';
    requests.slice(0, 20).forEach(function(r) {
        rows += `<tr data-id="${r.id}">
            <td>${r.id}</td>
            <td>${getName(properties, r.property_id)}</td>
            <td>${getName(categories, r.category_id)}</td>
            <td>${r.status || ''}</td>
            <td>${getUserName(r.assigned_to)}</td>
            <td>${r.due_date || ''}</td>
            <td class="action-btns">
                <button class="btn btn-sm btn-outline-primary viewBtn">View</button>
            </td>
        </tr>`;
    });
    $('#requestsTbody').html(rows || '<tr><td colspan="7" class="text-center">No requests found.</td></tr>');
    renderChart(requests);
}
function renderChart(requests) {
    // Group by date (created_at)
    let counts = {};
    requests.forEach(r => {
        let d = r.created_at ? r.created_at.slice(0,10) : '';
        if (!d) return;
        counts[d] = (counts[d]||0)+1;
    });
    let labels = Object.keys(counts).sort();
    let data = labels.map(l => counts[l]);
    if (window.reqChart) window.reqChart.destroy();
    let ctx = document.getElementById('requestsTrendChart').getContext('2d');
    window.reqChart = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: [{ label: 'Requests', data, borderColor: '#007bff', backgroundColor: 'rgba(0,123,255,0.1)', tension:0.3 }] },
        options: { responsive:true, plugins:{legend:{display:false}}, scales:{x:{title:{display:true,text:'Date'}},y:{title:{display:true,text:'Requests'}}} }
    });
}
function loadDashboard() {
    $.post(apiUrl + 'filter', { table: 'fms_maintenance_requests', filters: {} }, function(res) {
        allRequests = res || [];
        renderDashboard(allRequests);
    });
}
$('#reviewTbody').on('click', '.reviewBtn', function() {
    let id = $(this).closest('tr').data('id');
    window.location.href = '/supervisor_review/' + id;
});
$('#requestsTbody').on('click', '.viewBtn', function() {
    let id = $(this).closest('tr').data('id');
    window.location.href = '/assignment/' + id;
});
$('#applyFiltersBtn').on('click', applyFilters);
$('#resetFiltersBtn').on('click', resetFilters);
$(function() {
    loadLookups();
    setTimeout(loadDashboard, 500);
});
</script>
</body>
</html>
