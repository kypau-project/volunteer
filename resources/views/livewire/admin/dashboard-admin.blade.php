    <div>
        <div class="page-heading">
            <h3>Admin Dashboard</h3>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon purple mb-2">
                                                <i class="bi-people"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Total Users</h6>
                                            <h6 class="font-extrabold mb-0">{{$totalVolunteers}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon blue mb-2">
                                                <i class="bi-clock"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Jam Kontribusi</h6>
                                            <h6 class="font-extrabold mb-0">{{ number_format($totalHours, 2) }} Jam</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon green mb-2">
                                                <i class="bi-calendar-event"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Total Event</h6>
                                            <h6 class="font-extrabold mb-0">{{$totalEvents}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-4 py-4-5">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                            <div class="stats-icon red mb-2">
                                                <i class="bi-calendar-check"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                            <h6 class="text-muted font-semibold">Aktif Hari ini</h6>
                                            <h6 class="font-extrabold mb-0">{{ $activeToday ?? 0 }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Statistik User</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-6">
                                            <div class="text-center bg-primary text-white p-3 rounded">
                                                <h3 class="font-bold">{{ $roleStats['volunteer'] ?? 0 }}</h3>
                                                <p>Relawan</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="text-center bg-success text-white p-3 rounded">
                                                <h3 class="font-bold">{{ $roleStats['coordinator'] ?? 0 }}</h3>
                                                <p>Koordinator</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="text-center bg-info text-white p-3 rounded">
                                                <h3 class="font-bold">{{ $roleStats['admin'] ?? 0 }}</h3>
                                                <p>Admin</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="text-center bg-warning text-white p-3 rounded">
                                                <h3 class="font-bold">{{ $newThisMonth ?? 0 }}</h3>
                                                <p>User Baru Bulan {{ now()->format('F') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Grafik Pendaftaran Relawan {{ date('Y') }}</h4>
                                </div>
                                <div class="card-body">
                                    <canvas id="registrationChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Load Chart.js library
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js';
            script.onload = () => {
                initChart();
            };
            document.head.appendChild(script);
        } else {
            initChart();
        }

        function initChart() {
            const ctx = document.getElementById('registrationChart');
            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        data: chartData.data,
                        backgroundColor: 'rgba(102, 16, 242, 0.6)',
                        borderColor: 'rgba(102, 16, 242, 1)',
                        borderWidth: 1
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
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    </script>
    @endpush