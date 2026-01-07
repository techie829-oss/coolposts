<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ad Network Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Real-time Status Indicator -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div id="realtime-indicator" class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-gray-600">Live Updates</span>
                </div>
                <div class="flex space-x-2">
                    <button id="refresh-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-sync-alt mr-1"></i>Refresh
                    </button>
                    <button id="toggle-realtime" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        <i class="fas fa-pause mr-1"></i>Pause
                    </button>
                </div>
            </div>

            <!-- Real-time Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Active Networks</p>
                            <p id="active-networks" class="text-2xl font-bold">{{ $stats['active_networks'] ?? 0 }}</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-network-wired text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="active-networks-change" class="text-xs text-blue-100"></span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-100">Total Revenue</p>
                            <p id="total-revenue" class="text-2xl font-bold">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="total-revenue-change" class="text-xs text-green-100"></span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100">Total Clicks</p>
                            <p id="total-clicks" class="text-2xl font-bold">{{ number_format($stats['total_clicks'] ?? 0) }}</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-mouse-pointer text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="total-clicks-change" class="text-xs text-purple-100"></span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-100">Total Impressions</p>
                            <p id="total-impressions" class="text-2xl font-bold">{{ number_format($stats['total_impressions'] ?? 0) }}</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-eye text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="total-impressions-change" class="text-xs text-orange-100"></span>
                    </div>
                </div>
            </div>

            <!-- Real-time Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Click-Through Rate</p>
                            <p id="ctr" class="text-2xl font-bold text-gray-900">{{ $metrics['ctr'] ?? 3.6 }}%</p>
                        </div>
                        <div class="text-green-500">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="ctr-change" class="text-xs text-gray-500"></span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Cost Per Click</p>
                            <p id="cpc" class="text-2xl font-bold text-gray-900">${{ $metrics['cpc'] ?? 0.25 }}</p>
                        </div>
                        <div class="text-blue-500">
                            <i class="fas fa-tag text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="cpc-change" class="text-xs text-gray-500"></span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Revenue Per Click</p>
                            <p id="rpc" class="text-2xl font-bold text-gray-900">${{ $metrics['rpc'] ?? 0.28 }}</p>
                        </div>
                        <div class="text-green-500">
                            <i class="fas fa-coins text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="rpc-change" class="text-xs text-gray-500"></span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Fill Rate</p>
                            <p id="fill-rate" class="text-2xl font-bold text-gray-900">{{ $metrics['fill_rate'] ?? 95.2 }}%</p>
                        </div>
                        <div class="text-purple-500">
                            <i class="fas fa-percentage text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span id="fill-rate-change" class="text-xs text-gray-500"></span>
                    </div>
                </div>
            </div>

            <!-- Real-time Network Status -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Network Status</h3>
                    </div>
                    <div class="p-6">
                        <div id="network-list" class="space-y-4">
                            @foreach($networks as $network)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full {{ $network['status'] === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $network['name'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $network['type'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium {{ $network['status'] === 'active' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($network['status']) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Real-time Activity</h3>
                    </div>
                    <div class="p-6">
                        <div id="realtime-activity" class="space-y-3">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                                <p>Loading real-time activity...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        <!-- Account Settings Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Google AdSense Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Google AdSense</h3>
                            <p class="text-blue-100 text-sm">Display Advertising</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-ad text-xl"></i>
                        </div>
                    </div>

                    <form id="adsense-settings-form" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs text-blue-100 mb-1">Publisher ID</label>
                            <input type="text" name="adsense_publisher_id" value="{{ $settings->adsense_publisher_id ?? '' }}"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-blue-200 focus:ring-2 focus:ring-white focus:border-white"
                                   placeholder="pub-1234567890123456">
                        </div>
                        <div>
                            <label class="block text-xs text-blue-100 mb-1">Ad Unit ID</label>
                            <input type="text" name="adsense_ad_unit_id" value="{{ $settings->adsense_ad_unit_id ?? '' }}"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-blue-200 focus:ring-2 focus:ring-white focus:border-white"
                                   placeholder="1234567890">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" name="adsense_enabled" id="adsense_enabled"
                                       {{ $settings->adsense_enabled ?? false ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="adsense_enabled" class="ml-2 block text-sm text-white">Enable AdSense</label>
                            </div>
                            <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-save mr-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>

                <!-- AdMob Card -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">AdMob</h3>
                            <p class="text-green-100 text-sm">Mobile Advertising</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-mobile-alt text-xl"></i>
                        </div>
                    </div>

                    <form id="admob-settings-form" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs text-green-100 mb-1">App ID</label>
                            <input type="text" name="admob_app_id" value="{{ $settings->admob_app_id ?? '' }}"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-green-200 focus:ring-2 focus:ring-white focus:border-white"
                                   placeholder="ca-app-pub-1234567890123456~1234567890">
                        </div>
                        <div>
                            <label class="block text-xs text-green-100 mb-1">Banner Ad Unit ID</label>
                            <input type="text" name="admob_banner_unit_id" value="{{ $settings->admob_banner_unit_id ?? '' }}"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-green-200 focus:ring-2 focus:ring-white focus:border-white"
                                   placeholder="ca-app-pub-1234567890123456/1234567890">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" name="admob_enabled" id="admob_enabled"
                                       {{ $settings->admob_enabled ?? false ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="admob_enabled" class="ml-2 block text-sm text-white">Enable AdMob</label>
                            </div>
                            <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-save mr-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Facebook Ads Card -->
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Facebook Ads</h3>
                            <p class="text-indigo-100 text-sm">Social Advertising</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fab fa-facebook text-xl"></i>
                        </div>
                    </div>

                    <form id="facebook-settings-form" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs text-indigo-100 mb-1">App ID</label>
                            <input type="text" name="facebook_app_id" value="{{ $settings->facebook_app_id ?? '' }}"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-indigo-200 focus:ring-2 focus:ring-white focus:border-white"
                                   placeholder="123456789012345">
                        </div>
                        <div>
                            <label class="block text-xs text-indigo-100 mb-1">Ad Unit ID</label>
                            <input type="text" name="facebook_ad_unit_id" value="{{ $settings->facebook_ad_unit_id ?? '' }}"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-indigo-200 focus:ring-2 focus:ring-white focus:border-white"
                                   placeholder="123456789012345_123456789012345">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" name="facebook_enabled" id="facebook_enabled"
                                       {{ $settings->facebook_enabled ?? false ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="facebook_enabled" class="ml-2 block text-sm text-white">Enable Facebook Ads</label>
                            </div>
                            <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-save mr-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Revenue Settings Card -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">Revenue Settings</h3>
                            <p class="text-purple-100 text-sm">Earnings Configuration</p>
                        </div>
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                    </div>

                    <form id="revenue-settings-form" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs text-purple-100 mb-1">Default CPM Rate ($)</label>
                            <input type="number" name="default_cpm_rate" value="{{ $settings->default_cpm_rate ?? 2.50 }}"
                                   step="0.01" min="0"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-purple-200 focus:ring-2 focus:ring-white focus:border-white">
                        </div>
                        <div>
                            <label class="block text-xs text-purple-100 mb-1">Revenue Share (%)</label>
                            <input type="number" name="revenue_share_percentage" value="{{ $settings->revenue_share_percentage ?? 70 }}"
                                   step="1" min="0" max="100"
                                   class="w-full px-3 py-2 bg-white bg-opacity-10 border border-white border-opacity-30 rounded-md text-sm text-white placeholder-purple-200 focus:ring-2 focus:ring-white focus:border-white">
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-purple-100">
                                <span class="font-medium">Current Rate:</span> ${{ $settings->default_cpm_rate ?? 2.50 }} CPM
                            </div>
                            <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-save mr-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions & Live Updates</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="space-y-3">
                                <button id="test-connections" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    <i class="fas fa-plug mr-2"></i>Test Connections
                                </button>
                                <button id="clear-cache" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                                    <i class="fas fa-trash mr-2"></i>Clear Cache
                                </button>
                                <button id="generate-report" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                    <i class="fas fa-file-alt mr-2"></i>Generate Report
                                </button>
                            </div>

                            <div class="border-t pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Live Updates</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Auto-refresh</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="auto-refresh" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Update interval</span>
                                        <select id="update-interval" class="text-sm border border-gray-300 rounded px-2 py-1">
                                            <option value="5000">5 seconds</option>
                                            <option value="10000" selected>10 seconds</option>
                                            <option value="30000">30 seconds</option>
                                            <option value="60000">1 minute</option>
                                        </select>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Last updated: <span id="last-updated">{{ now()->format('H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time JavaScript -->
    <script>
        class RealTimeAdNetwork {
            constructor() {
                this.isRunning = true;
                this.updateInterval = 10000; // 10 seconds
                this.previousData = {};
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.startRealTimeUpdates();
                this.loadInitialData();
            }

            setupEventListeners() {
                // Toggle real-time updates
                document.getElementById('toggle-realtime').addEventListener('click', () => {
                    this.toggleRealTime();
                });

                // Manual refresh
                document.getElementById('refresh-btn').addEventListener('click', () => {
                    this.refreshData();
                });

                // Auto-refresh toggle
                document.getElementById('auto-refresh').addEventListener('click', (e) => {
                    if (!e.target.checked) {
                        this.stopRealTime();
                    } else {
                        this.startRealTimeUpdates();
                    }
                });

                // Update interval change
                document.getElementById('update-interval').addEventListener('change', (e) => {
                    this.updateInterval = parseInt(e.target.value);
                    if (this.isRunning) {
                        this.stopRealTime();
                        this.startRealTimeUpdates();
                    }
                });

                // Quick actions
                document.getElementById('test-connections').addEventListener('click', () => {
                    this.testConnections();
                });

                document.getElementById('clear-cache').addEventListener('click', () => {
                    this.clearCache();
                });

                document.getElementById('generate-report').addEventListener('click', () => {
                    this.generateReport();
                });

                // Account settings form submissions
                document.getElementById('adsense-settings-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.saveAdSenseSettings();
                });

                document.getElementById('admob-settings-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.saveAdMobSettings();
                });

                document.getElementById('revenue-settings-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.saveRevenueSettings();
                });

                document.getElementById('facebook-settings-form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.saveFacebookSettings();
                });
            }

            async loadInitialData() {
                await this.refreshData();
            }

            startRealTimeUpdates() {
                this.isRunning = true;
                this.updateRealTimeIndicator(true);
                this.updateToggleButton(true);
                this.scheduleNextUpdate();
            }

            stopRealTime() {
                this.isRunning = false;
                this.updateRealTimeIndicator(false);
                this.updateToggleButton(false);
            }

            toggleRealTime() {
                if (this.isRunning) {
                    this.stopRealTime();
                } else {
                    this.startRealTimeUpdates();
                }
            }

            scheduleNextUpdate() {
                if (!this.isRunning) return;

                setTimeout(() => {
                    this.refreshData();
                    this.scheduleNextUpdate();
                }, this.updateInterval);
            }

            async refreshData() {
                try {
                    const response = await fetch('{{ route("admin.ad-networks.real-time-earnings") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.updateDashboard(data);
                        this.updateLastUpdated();
                    }
                } catch (error) {
                    console.error('Error refreshing data:', error);
                }
            }

            updateDashboard(data) {
                // Update statistics with change indicators
                this.updateStat('active-networks', data.stats?.active_networks || 0, 'active-networks-change');
                this.updateStat('total-revenue', data.stats?.total_revenue || 0, 'total-revenue-change', true);
                this.updateStat('total-clicks', data.stats?.total_clicks || 0, 'total-clicks-change');
                this.updateStat('total-impressions', data.stats?.total_impressions || 0, 'total-impressions-change');

                // Update metrics with change indicators
                this.updateStat('ctr', data.metrics?.ctr || 3.6, 'ctr-change', false, '%');
                this.updateStat('cpc', data.metrics?.cpc || 0.25, 'cpc-change', true, '$');
                this.updateStat('rpc', data.metrics?.rpc || 0.28, 'rpc-change', true, '$');
                this.updateStat('fill-rate', data.metrics?.fill_rate || 95.2, 'fill-rate-change', false, '%');

                // Update network status
                if (data.networks) {
                    this.updateNetworkStatus(data.networks);
                }

                // Update real-time activity
                if (data.recent_activity) {
                    this.updateRealTimeActivity(data.recent_activity);
                }
            }

            updateStat(elementId, newValue, changeElementId, isCurrency = false, suffix = '') {
                const element = document.getElementById(elementId);
                const changeElement = document.getElementById(changeElementId);

                if (!element) return;

                const oldValue = parseFloat(element.textContent.replace(/[^0-9.-]/g, ''));
                const newValueNum = parseFloat(newValue);

                // Format the new value
                let formattedValue = newValueNum;
                if (isCurrency) {
                    formattedValue = '$' + newValueNum.toFixed(2);
                } else if (suffix === '%') {
                    formattedValue = newValueNum.toFixed(1) + '%';
                } else {
                    formattedValue = newValueNum.toLocaleString();
                }

                element.textContent = formattedValue;

                // Show change indicator
                if (this.previousData[elementId] !== undefined) {
                    const change = newValueNum - this.previousData[elementId];
                    if (change !== 0) {
                        const changeText = (change > 0 ? '+' : '') + change.toFixed(2) + suffix;
                        const changeColor = change > 0 ? 'text-green-500' : 'text-red-500';

                        changeElement.textContent = changeText;
                        changeElement.className = 'text-xs ' + changeColor;

                        // Animate the change
                        element.classList.add('animate-pulse');
                        setTimeout(() => element.classList.remove('animate-pulse'), 1000);
                    }
                }

                this.previousData[elementId] = newValueNum;
            }

            updateNetworkStatus(networks) {
                const networkList = document.getElementById('network-list');
                if (!networkList) return;

                networkList.innerHTML = networks.map(network =>
                    '<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">' +
                        '<div class="flex items-center space-x-3">' +
                            '<div class="w-3 h-3 rounded-full ' + (network.status === 'active' ? 'bg-green-500' : 'bg-red-500') + '"></div>' +
                            '<div>' +
                                '<p class="font-medium text-gray-900">' + network.name + '</p>' +
                                '<p class="text-sm text-gray-500">' + network.type + '</p>' +
                            '</div>' +
                        '</div>' +
                        '<span class="text-sm font-medium ' + (network.status === 'active' ? 'text-green-600' : 'text-red-600') + '">' +
                            network.status.charAt(0).toUpperCase() + network.status.slice(1) +
                        '</span>' +
                    '</div>'
                ).join('');
            }

            updateRealTimeActivity(activities) {
                const activityContainer = document.getElementById('realtime-activity');
                if (!activityContainer) return;

                activityContainer.innerHTML = activities.map(activity =>
                    '<div class="flex items-center space-x-3 p-2 bg-gray-50 rounded">' +
                        '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' +
                        '<div class="flex-1">' +
                            '<p class="text-sm text-gray-900">' + activity.message + '</p>' +
                            '<p class="text-xs text-gray-500">' + activity.time + '</p>' +
                        '</div>' +
                    '</div>'
                ).join('');
            }

            updateRealTimeIndicator(isActive) {
                const indicator = document.getElementById('realtime-indicator');
                if (indicator) {
                    indicator.className = 'w-3 h-3 rounded-full ' + (isActive ? 'bg-green-500 animate-pulse' : 'bg-gray-400');
                }
            }

            updateToggleButton(isRunning) {
                const button = document.getElementById('toggle-realtime');
                if (button) {
                    button.innerHTML = isRunning ?
                        '<i class="fas fa-pause mr-1"></i>Pause' :
                        '<i class="fas fa-play mr-1"></i>Resume';
                    button.className = 'bg-' + (isRunning ? 'green' : 'blue') + '-600 hover:bg-' + (isRunning ? 'green' : 'blue') + '-700 text-white px-3 py-1 rounded text-sm';
                }
            }

            updateLastUpdated() {
                const lastUpdated = document.getElementById('last-updated');
                if (lastUpdated) {
                    lastUpdated.textContent = new Date().toLocaleTimeString();
                }
            }

            async testConnections() {
                try {
                    const response = await fetch('{{ route("admin.ad-networks.test-connections") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', result.message);
                } catch (error) {
                    this.showNotification('error', 'Failed to test connections');
                }
            }

            async clearCache() {
                try {
                    const response = await fetch('{{ route("admin.ad-networks.clear-cache") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', result.message);
                } catch (error) {
                    this.showNotification('error', 'Failed to clear cache');
                }
            }

            async generateReport() {
                try {
                    const response = await fetch('{{ route("admin.ad-networks.earnings-report") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            start_date: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                            end_date: new Date().toISOString().split('T')[0]
                        })
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', 'Report generated successfully');
                } catch (error) {
                    this.showNotification('error', 'Failed to generate report');
                }
            }

                        async saveAdSenseSettings() {
                try {
                    const form = document.getElementById('adsense-settings-form');
                    const formData = new FormData(form);

                    const response = await fetch('{{ route("admin.ad-networks.account-settings") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', result.message);

                    if (result.success) {
                        // Refresh the page to show updated settings
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } catch (error) {
                    this.showNotification('error', 'Failed to save AdSense settings');
                }
            }

            async saveAdMobSettings() {
                try {
                    const form = document.getElementById('admob-settings-form');
                    const formData = new FormData(form);

                    const response = await fetch('{{ route("admin.ad-networks.account-settings") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', result.message);

                    if (result.success) {
                        // Refresh the page to show updated settings
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } catch (error) {
                    this.showNotification('error', 'Failed to save AdMob settings');
                }
            }

                        async saveRevenueSettings() {
                try {
                    const form = document.getElementById('revenue-settings-form');
                    const formData = new FormData(form);

                    const response = await fetch('{{ route("admin.ad-networks.account-settings") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', result.message);

                    if (result.success) {
                        // Refresh the page to show updated settings
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } catch (error) {
                    this.showNotification('error', 'Failed to save revenue settings');
                }
            }

            async saveFacebookSettings() {
                try {
                    const form = document.getElementById('facebook-settings-form');
                    const formData = new FormData(form);

                    const response = await fetch('{{ route("admin.ad-networks.account-settings") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const result = await response.json();
                    this.showNotification(result.success ? 'success' : 'error', result.message);

                    if (result.success) {
                        // Refresh the page to show updated settings
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } catch (error) {
                    this.showNotification('error', 'Failed to save Facebook Ads settings');
                }
            }

            showNotification(type, message) {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 p-4 rounded-lg text-white z-50 ' + (type === 'success' ? 'bg-green-500' : 'bg-red-500');
                notification.textContent = message;

                document.body.appendChild(notification);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }
        }

        // Initialize real-time functionality when page loads
        document.addEventListener('DOMContentLoaded', () => {
            new RealTimeAdNetwork();
        });
    </script>
</x-app-layout>
