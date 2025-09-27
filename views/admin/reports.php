<?php
$title = 'Reports & Analytics';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Reports & Analytics</h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="refreshData()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateFrom" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="dateFrom" value="<?= date('Y-m-01') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dateTo" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="dateTo" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-select" id="reportType">
                                    <option value="overview">Overview</option>
                                    <option value="revenue">Revenue</option>
                                    <option value="bookings">Bookings</option>
                                    <option value="users">Users</option>
                                    <option value="hotels">Hotels</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary w-100" onclick="generateReport()">
                                    <i class="fas fa-chart-bar"></i> Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">UGX <?= number_format($metrics['total_revenue'] ?? 0) ?></h4>
                            <p class="mb-0">Total Revenue</p>
                            <small class="opacity-75">
                                <i class="fas fa-arrow-<?= ($metrics['revenue_change'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                <?= abs($metrics['revenue_change'] ?? 0) ?>% vs last period
                            </small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $metrics['total_bookings'] ?? 0 ?></h4>
                            <p class="mb-0">Total Bookings</p>
                            <small class="opacity-75">
                                <i class="fas fa-arrow-<?= ($metrics['bookings_change'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                <?= abs($metrics['bookings_change'] ?? 0) ?>% vs last period
                            </small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $metrics['total_users'] ?? 0 ?></h4>
                            <p class="mb-0">Total Users</p>
                            <small class="opacity-75">
                                <i class="fas fa-arrow-<?= ($metrics['users_change'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                <?= abs($metrics['users_change'] ?? 0) ?>% vs last period
                            </small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $metrics['total_hotels'] ?? 0 ?></h4>
                            <p class="mb-0">Total Hotels</p>
                            <small class="opacity-75">
                                <i class="fas fa-arrow-<?= ($metrics['hotels_change'] ?? 0) >= 0 ? 'up' : 'down' ?>"></i>
                                <?= abs($metrics['hotels_change'] ?? 0) ?>% vs last period
                            </small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hotel fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Revenue Trend
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Booking Status Chart -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> Booking Status
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="bookingStatusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row">
        <!-- Top Performing Hotels -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy"></i> Top Performing Hotels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Hotel</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($top_hotels) && !empty($top_hotels)): ?>
                                    <?php foreach ($top_hotels as $hotel): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= htmlspecialchars($hotel['image_url']) ?>" 
                                                         alt="Hotel" class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($hotel['name']) ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars($hotel['location']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary"><?= $hotel['bookings'] ?></span></td>
                                            <td><strong>UGX <?= number_format($hotel['revenue']) ?></strong></td>
                                            <td>
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $hotel['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Growth -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus"></i> User Growth
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt"></i> Monthly Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Revenue</th>
                                    <th>Bookings</th>
                                    <th>New Users</th>
                                    <th>New Hotels</th>
                                    <th>Avg. Booking Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($monthly_data) && !empty($monthly_data)): ?>
                                    <?php foreach ($monthly_data as $month): ?>
                                        <tr>
                                            <td><strong><?= $month['month'] ?></strong></td>
                                            <td><strong class="text-success">UGX <?= number_format($month['revenue']) ?></strong></td>
                                            <td><span class="badge bg-primary"><?= $month['bookings'] ?></span></td>
                                            <td><span class="badge bg-success"><?= $month['new_users'] ?></span></td>
                                            <td><span class="badge bg-info"><?= $month['new_hotels'] ?></span></td>
                                            <td><strong>UGX <?= number_format($month['avg_booking_value']) ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_data['revenue']['labels'] ?? []) ?>,
            datasets: [{
                label: 'Revenue (UGX)',
                data: <?= json_encode($chart_data['revenue']['data'] ?? []) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'UGX ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Booking Status Chart
    const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    new Chart(bookingStatusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chart_data['booking_status']['labels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($chart_data['booking_status']['data'] ?? []) ?>,
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // User Growth Chart
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chart_data['user_growth']['labels'] ?? []) ?>,
            datasets: [{
                label: 'New Users',
                data: <?= json_encode($chart_data['user_growth']['data'] ?? []) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function generateReport() {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const reportType = document.getElementById('reportType').value;
    
    if (!dateFrom || !dateTo) {
        alert('Please select both from and to dates');
        return;
    }
    
    if (new Date(dateTo) < new Date(dateFrom)) {
        alert('To date must be after from date');
        return;
    }
    
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    button.disabled = true;
    
    // Simulate report generation (replace with actual API call)
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        
        // Update charts and data based on selected date range
        updateCharts(dateFrom, dateTo, reportType);
        
        // Show success message
        showAlert('Report generated successfully!', 'success');
    }, 2000);
}

function updateCharts(dateFrom, dateTo, reportType) {
    // This would typically make an AJAX call to get new data
    // For now, we'll just show a message
    console.log('Updating charts for:', { dateFrom, dateTo, reportType });
}

function exportReport(format) {
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    const reportType = document.getElementById('reportType').value;
    
    if (!dateFrom || !dateTo) {
        alert('Please select both from and to dates');
        return;
    }
    
    // Create export URL
    const exportUrl = `/admin/reports/export?format=${format}&from=${dateFrom}&to=${dateTo}&type=${reportType}`;
    
    // Open in new window to trigger download
    window.open(exportUrl, '_blank');
}

function refreshData() {
    // Reload the page to get fresh data
    window.location.reload();
}

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
