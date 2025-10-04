@section('title', 'Dashboard')
@push('css')
<style>
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
@endpush

@push('js')
<script>
    new ApexCharts(document.querySelector("#grafik-laporan-pie"), {
        series: @json([$laporanPending, $laporanProses, $laporanSelesai]),
        labels: ['Pending', 'Diproses', 'Selesai'],
        colors: ['#ff7976', '#57caeb', '#5DDAB4'],
        chart: {
            type: 'pie',
            height: 350
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        legend: {
            position: 'bottom'
        },
    }).render();
</script>
@endpush
<div>
    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="bi-chat"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Laporan</h6>
                                        <h6 class="font-extrabold mb-0">{{$totalLaporan}}</h6>
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
                                            <i class="bi-hourglass" style="animation: spin 3s linear infinite;"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Pending</h6>
                                        <h6 class="font-extrabold mb-0">{{$laporanPending}}</h6>
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
                                            <i class="bi-arrow-repeat" style="animation: spin 3s linear infinite;"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Diproses</h6>
                                        <h6 class="font-extrabold mb-0">{{$laporanProses}}</h6>
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
                                            <i class="bi-check-circle"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Selesai</h6>
                                        <h6 wire:poll.5s class="font-extrabold mb-0">{{$laporanSelesai}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Status Laporan Pengaduan</h4>
                            </div>
                            <div class="card-body">
                                <div id="grafik-laporan-pie" style="min-height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>