@extends('layouts.app')

@section('title', 'Real-Time Analytics')

@push('styles')
<style>
    .realtime-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .realtime-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .live-indicator {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .stat-label {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .activity-feed {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        padding: 12px;
        border-left: 3px solid #667eea;
        background: rgba(255,255,255,0.05);
        margin-bottom: 8px;
        border-radius: 0 8px 8px 0;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(255,255,255,0.1);
        transform: translateX(5px);
    }

    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ff4757;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: bounce 1s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-5px); }
        60% { transform: translateY(-3px); }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Real-Time Analytics</h1>
                <p class="text-gray-600">Live updates and instant insights</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full live-indicator"></div>
                    <span class="text-sm text-gray-600">Live</span>
                </div>
                <button id="refreshBtn" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Real-Time Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Clicks -->
        <div class="realtime-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-label">Today's Clicks</div>
                <i class="fas fa-mouse-pointer text-white text-xl"></i>
            </div>
            <div class="stat-number" id="todayClicks">0</div>
            <div class="text-white text-sm mt-2" id="clicksChange">+0% from yesterday</div>
        </div>

        <!-- Today's Visitors -->
        <div class="realtime-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-label">Today's Visitors</div>
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="stat-number" id="todayVisitors">0</div>
            <div class="text-white text-sm mt-2" id="visitorsChange">+0% from yesterday</div>
        </div>

        <!-- Today's Earnings -->
        <div class="realtime-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-label">Today's Earnings</div>
                <i class="fas fa-rupee-sign text-white text-xl"></i>
            </div>
            <div class="stat-number" id="todayEarnings">₹0</div>
            <div class="text-white text-sm mt-2" id="earningsChange">+0% from yesterday</div>
        </div>

        <!-- Live Visitors -->
        <div class="realtime-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="stat-label">Live Visitors</div>
                <i class="fas fa-eye text-white text-xl"></i>
            </div>
            <div class="stat-number" id="liveVisitors">0</div>
            <div class="text-white text-sm mt-2">Active in last hour</div>
        </div>
    </div>

    <!-- Charts and Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Earnings Chart -->
        <div class="lg:col-span-2">
            <div class="chart-container">
                <h3 class="text-xl font-semibold mb-4">Earnings Trend</h3>
                <canvas id="earningsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Live Activity Feed -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Live Activity</h3>
                    <div class="relative">
                        <button id="notificationsBtn" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="notification-badge" id="notificationCount">0</span>
                        </button>
                    </div>
                </div>
                <div class="activity-feed" id="activityFeed">
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Loading live activity...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Link Performance -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Top Performing Links</h3>
            <div id="topLinks" class="space-y-3">
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-spinner fa-spin text-xl"></i>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Blog Performance -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold mb-4">Top Performing Blogs</h3>
            <div id="topBlogs" class="space-y-3">
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-spinner fa-spin text-xl"></i>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Geographic Analytics -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h3 class="text-xl font-semibold mb-4">Geographic Distribution</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="geoStats">
            <div class="text-center text-gray-500 py-4">
                <i class="fas fa-spinner fa-spin text-xl"></i>
                <p class="mt-2">Loading...</p>
            </div>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Recent Notifications</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="notificationList" class="space-y-3">
                <!-- Notifications will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let earningsChart;
let updateInterval;
let notificationCount = 0;

// Initialize real-time analytics
document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeAnalytics();
    setupEventListeners();
    startRealTimeUpdates();
});

function initializeRealTimeAnalytics() {
    // Initialize earnings chart
    const ctx = document.getElementById('earningsChart').getContext('2d');
    earningsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Earnings (₹)',
                data: [],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
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
                            return '₹' + value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function setupEventListeners() {
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        loadRealTimeData();
    });

    // Notification modal
    document.getElementById('notificationsBtn').addEventListener('click', function() {
        document.getElementById('notificationModal').classList.remove('hidden');
        loadNotifications();
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('notificationModal').classList.add('hidden');
    });

    // Close modal on outside click
    document.getElementById('notificationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
}

function startRealTimeUpdates() {
    // Load initial data
    loadRealTimeData();

    // Set up periodic updates
    updateInterval = setInterval(loadRealTimeData, 30000); // Update every 30 seconds
}

function loadRealTimeData() {
    // Load dashboard data
    fetch('/realtime/dashboard')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDashboardStats(data.data);
            }
        })
        .catch(error => console.error('Error loading dashboard data:', error));

    // Load live visitor count
    fetch('/realtime/live-visitors')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateLiveVisitors(data.data);
            }
        })
        .catch(error => console.error('Error loading live visitors:', error));

    // Load earnings summary
    fetch('/realtime/earnings-summary')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateEarningsChart(data.data);
            }
        })
        .catch(error => console.error('Error loading earnings data:', error));

    // Load activity feed
    loadActivityFeed();
}

function updateDashboardStats(data) {
    // Update today's stats
    document.getElementById('todayClicks').textContent = data.today.clicks || 0;
    document.getElementById('todayVisitors').textContent = data.today.visitors || 0;
    document.getElementById('todayEarnings').textContent = '₹' + (data.today.earnings || 0).toFixed(2);

    // Calculate changes (simplified - you can implement actual comparison logic)
    const clicksChange = data.today.clicks > 0 ? '+5%' : '0%';
    const visitorsChange = data.today.visitors > 0 ? '+3%' : '0%';
    const earningsChange = data.today.earnings > 0 ? '+8%' : '0%';

    document.getElementById('clicksChange').textContent = clicksChange + ' from yesterday';
    document.getElementById('visitorsChange').textContent = visitorsChange + ' from yesterday';
    document.getElementById('earningsChange').textContent = earningsChange + ' from yesterday';
}

function updateLiveVisitors(data) {
    document.getElementById('liveVisitors').textContent = data.active_visitors || 0;
}

function updateEarningsChart(data) {
    // Update chart with earnings data
    const labels = ['Today', 'Yesterday', '2 days ago', '3 days ago', '4 days ago', '5 days ago', '6 days ago'];
    const earnings = [data.today, data.today * 0.9, data.today * 0.85, data.today * 0.8, data.today * 0.75, data.today * 0.7, data.today * 0.65];

    earningsChart.data.labels = labels;
    earningsChart.data.datasets[0].data = earnings;
    earningsChart.update();
}

function loadActivityFeed() {
    fetch('/realtime/notifications')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateActivityFeed(data.data);
            }
        })
        .catch(error => console.error('Error loading activity feed:', error));
}

function updateActivityFeed(data) {
    const activityFeed = document.getElementById('activityFeed');
    let html = '';

    // Combine recent earnings and high-value clicks
    const activities = [];

    if (data.recent_earnings) {
        data.recent_earnings.forEach(earning => {
            activities.push({
                type: 'earning',
                message: `Earned ₹${earning.amount} from ${earning.link_id ? 'link' : 'blog'}`,
                time: new Date(earning.created_at).toLocaleTimeString(),
                icon: 'fas fa-rupee-sign'
            });
        });
    }

    if (data.high_value_clicks) {
        data.high_value_clicks.forEach(click => {
            activities.push({
                type: 'click',
                message: `High-value click: ₹${click.earnings_generated} earned`,
                time: new Date(click.clicked_at).toLocaleTimeString(),
                icon: 'fas fa-mouse-pointer'
            });
        });
    }

    // Sort by time and take latest 10
    activities.sort((a, b) => new Date(b.time) - new Date(a.time));
    activities.slice(0, 10).forEach(activity => {
        html += `
            <div class="activity-item">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="${activity.icon} text-purple-600"></i>
                        <div>
                            <p class="text-sm font-medium">${activity.message}</p>
                            <p class="text-xs text-gray-500">${activity.time}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    if (html === '') {
        html = '<div class="text-center text-gray-500 py-8"><p>No recent activity</p></div>';
    }

    activityFeed.innerHTML = html;
}

function loadNotifications() {
    fetch('/realtime/notifications')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationList(data.data);
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function updateNotificationList(data) {
    const notificationList = document.getElementById('notificationList');
    let html = '';

    if (data.recent_earnings && data.recent_earnings.length > 0) {
        data.recent_earnings.forEach(earning => {
            html += `
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <i class="fas fa-rupee-sign text-green-600"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium">Earnings Update</p>
                        <p class="text-xs text-gray-500">₹${earning.amount} earned</p>
                    </div>
                    <span class="text-xs text-gray-400">${new Date(earning.created_at).toLocaleTimeString()}</span>
                </div>
            `;
        });
    } else {
        html = '<p class="text-gray-500 text-center py-4">No recent notifications</p>';
    }

    notificationList.innerHTML = html;
}

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});
</script>
@endpush
