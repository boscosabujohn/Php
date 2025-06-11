<!-- Technician Dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Technician Dashboard</title>
    <link rel="stylesheet" href="/theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: var(--background); color: var(--foreground); font-family: var(--font-sans); }
        .dashboard-card { background: var(--card); color: var(--card-foreground); border-radius: var(--radius); box-shadow: var(--shadow-md); border: 1px solid var(--border); padding: 2rem 1.5rem; max-width: 400px; margin: 5vh auto; text-align: center; }
        .dashboard-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 1.2rem; }
    </style>
</head>
<body>
    <div class="container-fluid py-4" style="max-width:100vw;">
        <h2 class="mb-4" style="font-size:1.4rem;">Technician Dashboard</h2>
        <div class="filter-bar" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;align-items:center;">
            <label>From: <input type="date" id="filterFrom" class="form-control form-control-sm d-inline-block" style="width:auto;"></label>
            <label>To: <input type="date" id="filterTo" class="form-control form-control-sm d-inline-block" style="width:auto;"></label>
            <label>Status:
                <select id="filterStatus" class="form-select form-select-sm d-inline-block" style="width:auto;">
                    <option value="">All</option>
                    <option value="Open">Open</option>
                    <option value="In Progress">In Progress</option>
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
        <div class="dashboard-metrics" style="display:flex;gap:2rem;margin-bottom:2rem;flex-wrap:wrap;">
            <div class="metric-box" style="flex:1 1 200px;min-width:200px;background:var(--muted);color:var(--muted-foreground);border-radius:var(--radius);padding:1.5rem 1rem;text-align:center;box-shadow:var(--shadow-sm);">
                <div class="metric-title" style="font-size:1.1rem;font-weight:600;margin-bottom:0.5rem;">Assigned Tasks</div>
                <div class="metric-value" id="assignedCount" style="font-size:2.2rem;font-weight:700;">0</div>
            </div>
            <div class="metric-box" style="flex:1 1 200px;min-width:200px;background:var(--muted);color:var(--muted-foreground);border-radius:var(--radius);padding:1.5rem 1rem;text-align:center;box-shadow:var(--shadow-sm);">
                <div class="metric-title">In Progress</div>
                <div class="metric-value" id="inProgressCount">0</div>
            </div>
            <div class="metric-box" style="flex:1 1 200px;min-width:200px;background:var(--muted);color:var(--muted-foreground);border-radius:var(--radius);padding:1.5rem 1rem;text-align:center;box-shadow:var(--shadow-sm);">
                <div class="metric-title">Completed</div>
                <div class="metric-value text-success" id="completedCount">0</div>
            </div>
            <div class="metric-box" style="flex:1 1 200px;min-width:200px;background:var(--muted);color:var(--muted-foreground);border-radius:var(--radius);padding:1.5rem 1rem;text-align:center;box-shadow:var(--shadow-sm);">
                <div class="metric-title">Delayed</div>
                <div class="metric-value text-danger" id="delayedCount">0</div>
            </div>
        </div>
        <div class="chart-container mb-4" style="background:var(--card);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:1rem;margin-bottom:2rem;">
            <canvas id="tasksTrendChart" height="80"></canvas>
        </div>
        <div class="card p-2 mb-4">
            <h5 class="mb-3">My Assigned Tasks</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="tasksTable" style="font-size:0.98em;">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Property</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTbody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
    const apiUrl = '/api/crud/';
    let properties = [], categories = [], userId = null, allTasks = [];
    function getName(arr, id) { let o = arr.find(x => x.id == id); return o ? o.name : ''; }
    function loadLookups() {
        $.post(apiUrl + 'filter', { table: 'fms_properties', filters: {} }, function(res) {
            properties = res || [];
            let opts = '<option value="">All</option>';
            properties.forEach(p => opts += `<option value="${p.id}">${p.name}</option>`);
            $('#filterProperty').html(opts);
        });
        $.post(apiUrl + 'filter', { table: 'fms_lookup_values', filters: { type: 'Category' } }, function(res) { categories = res || []; });
    }
    function applyFilters() {
        let from = $('#filterFrom').val();
        let to = $('#filterTo').val();
        let status = $('#filterStatus').val();
        let property = $('#filterProperty').val();
        let filtered = allTasks.filter(r => {
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
        renderDashboard(allTasks);
    }
    function renderDashboard(tasks) {
        $('#assignedCount').text(tasks.length);
        $('#inProgressCount').text(tasks.filter(r => r.status === 'In Progress').length);
        $('#completedCount').text(tasks.filter(r => r.status === 'Completed').length);
        $('#delayedCount').text(tasks.filter(r => r.status !== 'Completed' && r.due_date && r.due_date < new Date().toISOString().slice(0,10)).length);
        let rows = '';
        tasks.forEach(function(r) {
            rows += `<tr data-id="${r.id}">
                <td>${r.id}</td>
                <td>${getName(properties, r.property_id)}</td>
                <td>${getName(categories, r.category_id)}</td>
                <td>${r.status || ''}</td>
                <td>${r.due_date || ''}</td>
                <td class="action-btns">
                    <button class="btn btn-sm btn-outline-primary viewBtn">View</button>
                    <button class="btn btn-sm btn-outline-success feedbackBtn">Feedback</button>
                </td>
            </tr>`;
        });
        $('#tasksTbody').html(rows || '<tr><td colspan="6" class="text-center">No tasks assigned.</td></tr>');
        renderChart(tasks);
    }
    function renderChart(tasks) {
        // Group by date (created_at)
        let counts = {};
        tasks.forEach(r => {
            let d = r.created_at ? r.created_at.slice(0,10) : '';
            if (!d) return;
            counts[d] = (counts[d]||0)+1;
        });
        let labels = Object.keys(counts).sort();
        let data = labels.map(l => counts[l]);
        if (window.tasksChart) window.tasksChart.destroy();
        let ctx = document.getElementById('tasksTrendChart').getContext('2d');
        window.tasksChart = new Chart(ctx, {
            type: 'line',
            data: { labels, datasets: [{ label: 'Tasks', data, borderColor: '#007bff', backgroundColor: 'rgba(0,123,255,0.1)', tension:0.3 }] },
            options: { responsive:true, plugins:{legend:{display:false}}, scales:{x:{title:{display:true,text:'Date'}},y:{title:{display:true,text:'Tasks'}}} }
        });
    }
    function loadDashboard() {
        userId = 1;
        $.post(apiUrl + 'filter', { table: 'fms_maintenance_requests', filters: { assigned_to: userId } }, function(res) {
            allTasks = res || [];
            renderDashboard(allTasks);
        });
    }
    $('#tasksTbody').on('click', '.viewBtn', function() {
        let id = $(this).closest('tr').data('id');
        window.location.href = '/assignment/' + id;
    });
    $('#tasksTbody').on('click', '.feedbackBtn', function() {
        let id = $(this).closest('tr').data('id');
        window.location.href = '/technician_feedback/' + id;
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
