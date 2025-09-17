@extends('layouts.app')
@section('title', 'Dashboard')

@section('contents')
<style>
    .calendar-sunday {
        color: red;
        font-weight: bold;
    }

    .calendar-today {
        background-color: #d1ecf1;
        font-weight: bold;
        border-radius: 50%;
    }

    .calendar-holiday {
        background-color: #f8d7da;
        font-weight: bold;
        color: red;
        border-radius: 5px;
    }

    tbody td {
    text-align: center;
    vertical-align: middle;
}
</style>

<div class="row">

    <!-- Statistik Utama -->
    <div class="row">
        <!-- Total Karyawan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid rgb(80, 78, 51);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgb(175, 174, 118);">Total Karyawan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahKaryawan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Jabatan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid rgb(175, 174, 118);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgb(80, 78, 51);">Total Jabatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahJabatan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid rgb(80, 78, 51);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgb(175, 174, 118);">Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahUser }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Gaji Bersih Tahun Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2" style="border-left: 4px solid rgb(175, 174, 118);">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: rgb(80, 78, 51);">
                                Total Gaji Bersih Tahun Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalGajiBersih, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Riwayat Penggajian -->
        <!-- <div class="col-lg-12 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0 font-weight-bold">Riwayat Penggajian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Tanggal Proses</th>
                                <th>Periode Gaji</th>
                                <th>Jumlah Karyawan</th>
                                <th>Total Gaji Bersih</th>
                                <th>Total PPh 21</th>
                            </tr>
                        </thead>
                        <tbody>
                          @forelse($riwayat as $r)
    <tr>
        <td>{{ \Carbon\Carbon::parse($r->tanggal_proses)->format('d M Y') }}</td>
        <td>{{ \Carbon\Carbon::create()->month($r->bulan)->translatedFormat('F') }} {{ $r->tahun }}</td>
        <td>{{ $r->jumlah_karyawan }}</td>
        <td>Rp {{ number_format($r->total_gaji_bersih, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($r->total_pph21, 0, ',', '.') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-muted">Belum ada riwayat penggajian.</td>
    </tr>
@endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->

        <!-- Load Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div class="row">
            <!-- Grafik Total Gaji dan PPh (2/3 Lebar) -->
            <div class="col-lg-6 mb-4 d-flex justify-content-center">
                <div class="card shadow mb-4" style="width: 100%; max-width: 700px;">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: rgb(80, 78, 51);">Diagram Batang Total Gaji Bersih dan PPh 21</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="gajiBarChart" style="width:100%; height:300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Kehadiran (1/3 Lebar) -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4" style="height: 94%;">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: rgb(80, 78, 51);">Grafik Kehadiran {{ $tahunIni }}</h6>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 300px;">
                        <canvas id="lineChartKehadiran" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>


            <!-- Kalender -->
            @php
            use Carbon\Carbon;

            $selectedMonth = request()->query('month', Carbon::now()->month);
            $selectedYear = request()->query('year', Carbon::now()->year);
            $date = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
            $monthName = $date->translatedFormat('F Y');
            $daysInMonth = $date->daysInMonth;
            $firstDayOfWeek = $date->dayOfWeek;
            $weekDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $yearsRange = range(2020, 2030);

            // Contoh data libur nasional (bisa ambil dari DB/API)
            $holidays = [
            "$selectedYear-01-01" => 'Tahun Baru Masehi',
            "$selectedYear-05-01" => 'Hari Buruh',
            "$selectedYear-08-17" => 'Hari Kemerdekaan RI',
            "$selectedYear-12-25" => 'Hari Raya Natal',
            ];

            // Ambil hanya libur bulan ini
            $monthlyHolidays = collect($holidays)->filter(function ($desc, $dateStr) use ($selectedMonth, $selectedYear) {
            $dateObj = Carbon::parse($dateStr);
            return $dateObj->month == $selectedMonth && $dateObj->year == $selectedYear;
            });
            @endphp

            <div class="col-lg-12 mb-4"> <!-- UBAH dari col-lg-12 -->
                <div class="card shadow mb-4">
                    <div class="card-header text-white d-flex justify-content-between align-items-center py-2 px-3" style="background-color: rgb(80, 78, 51);">
                        <h6 class="m-0 font-weight-bold" style="font-size: 1rem; color: rgb(254, 254, 253);">Kalender {{ $monthName }}</h6>

                        <form method="GET" class="form-inline">
                            <select name="month" class="form-control form-control-sm mr-2" onchange="this.form.submit()" style="font-size: 0.75rem;">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                    @endfor
                            </select>
                            <select name="year" class="form-control form-control-sm" onchange="this.form.submit()" style="font-size: 0.75rem;">
                                @foreach ($yearsRange as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="card-body p-2" style="font-size: 0.75rem;">
                        <table class="table table-bordered text-center mb-2" style="table-layout: fixed; font-size: 0.7rem;">
                            <thead>
                                <tr>
                                    @foreach ($weekDays as $day)
                                    <th class="{{ $day == 'Minggu' ? 'calendar-sunday' : '' }}"
                                        style="padding: 4px; width: 14.28%;">
                                        {{ $day }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php $currentDay = 1; $started = false; @endphp
                                @for ($row = 0; $row < 6; $row++)
                                    <tr>
                                    @for ($col = 0; $col < 7; $col++)
                                        @php
                                        $isSunday=$col==0;
                                        $dateStr=sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $currentDay);
                                        $isToday=$currentDay==now()->day && $selectedMonth == now()->month && $selectedYear == now()->year;
                                        $isHoliday = array_key_exists($dateStr, $holidays);
                                        @endphp

                                        @if (!$started && $col == $firstDayOfWeek)
                                        @php $started = true; @endphp
                                        @endif

                                        @if ($started && $currentDay <= $daysInMonth)
                                            <td class="{{ $isSunday ? 'calendar-sunday' : '' }} {{ $isToday ? 'calendar-today' : '' }} {{ $isHoliday ? 'calendar-holiday' : '' }}"
                                            style="padding: 4px; height: 30px;">
                                            {{ $currentDay }}
                                            </td>
                                            @php $currentDay++; @endphp
                                            @else
                                            <td style="padding: 4px; height: 30px;">&nbsp;</td>
                                            @endif
                                            @endfor
                                            </tr>
                                            @endfor
                            </tbody>
                        </table>

                        @if ($monthlyHolidays->count())
                        <div class="mt-2">
                            <strong>Daftar Hari Libur:</strong>
                            <ul class="pl-3 mb-0">
                                @foreach ($monthlyHolidays as $date => $desc)
                                <li>{{ \Carbon\Carbon::parse($date)->format('d M Y') }} - {{ $desc }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                        <div class="mt-2 text-muted"><em>Tidak ada hari libur nasional bulan ini.</em></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <script>
            const labels = @json($labels);
            const totalGaji = @json($totalGaji);
            const totalPph = @json($totalPph);

            // Diagram batang untuk Total Gaji Bersih dan PPh 21
            const ctxGajiBar = document.getElementById('gajiBarChart').getContext('2d');
            const gajiBarChart = new Chart(ctxGajiBar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Total Gaji Bersih',
                            data: totalGaji,
                            backgroundColor: 'rgba(109, 79, 9, 0.7)', // biru
                            borderColor: 'rgb(84, 67, 14)',
                            borderWidth: 1
                        },
                        {
                            label: 'Total PPh 21',
                            data: totalPph,
                            backgroundColor: 'rgba(216, 178, 28, 0.7)', // merah
                            borderColor: 'rgb(227, 193, 21)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Diagram Batang Total Gaji Bersih dan PPh 21 per Bulan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Format angka pakai ribuan (opsional)
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Pie chart kehadiran (sama seperti sebelumnya)
            const ctx = document.getElementById('lineChartKehadiran').getContext('2d');

            const lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($bulanLabels),
                    datasets: [{
                            label: 'Masuk',
                            data: @json($masukData),
                            borderColor: 'rgb(42, 39, 7)',
                            backgroundColor: 'rgba(49, 46, 10, 0.2)',
                            fill: false,
                            tension: 0.3,
                        },
                        {
                            label: 'Izin',
                            data: @json($izinData),
                            borderColor: 'rgb(106, 78, 8)',
                            backgroundColor: 'rgba(71, 52, 6, 0.2)',
                            fill: false,
                            tension: 0.3,
                        },
                        {
                            label: 'Cuti',
                            data: @json($cutiData),
                            borderColor: 'rgb(161, 115, 5)',
                            backgroundColor: 'rgba(191, 140, 11, 0.2)',
                            fill: false,
                            tension: 0.3,
                        },
                        {
                            label: 'Sakit',
                            data: @json($sakitData),
                            borderColor: 'rgb(239, 201, 118)',
                            backgroundColor: 'rgba(237, 184, 114, 0.2)',
                            fill: false,
                            tension: 0.3,
                        },
                        {
                            label: 'Alpha',
                            data: @json($alphaData),
                            borderColor: 'rgb(228, 137, 9)',
                            backgroundColor: 'rgba(214, 138, 17, 0.2)',
                            fill: false,
                            tension: 0.3,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Hari'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        }
                    }
                }
            });
        </script>

         <!-- Tabel Tarif ptkp (2025) -->
         <div class="col-lg-6 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background-color: rgb(95, 93, 61); border-color: rgb(99, 97, 63);">
            <h6 class="m-0 font-weight-bold" style="color: white;">Tarif Efektif Bulanan Berdasarkan PTKP (2025)</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead style="background-color: rgb(223, 220, 185); color: rgb(80, 78, 51);">
                        <tr>
                            <th style="text-align: center; vertical-align: middle;">Kategori TER</th>
                            <th style="text-align: center; vertical-align: middle;">Status Penghasilan Tidak Kena Pajak (PTKP)</th>
                            <th style="text-align: center; vertical-align: middle;">Nilai PTKP (Rp)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Kategori A -->
                        <tr>
                            <td rowspan="3" style="text-align: center; vertical-align: middle;">TER Kategori A</td>
                            <td>Tidak kawin 0 tanggungan (TK/0)</td>
                            <td style="text-align: right;">54.000.000</td>
                        </tr>
                        <tr>
                            <td>Tidak kawin 1 tanggungan (TK/1)</td>
                            <td style="text-align: right;">58.500.000</td>
                        </tr>
                        <tr>
                            <td>Kawin 0 tanggungan (K/0)</td>
                            <td style="text-align: right;">58.500.000</td>
                        </tr>

                        <!-- Kategori B -->
                        <tr>
                            <td rowspan="4" style="text-align: center; vertical-align: middle;">TER Kategori B</td>
                            <td>Tidak kawin 2 tanggungan (TK/2)</td>
                            <td style="text-align: right;">63.000.000</td>
                        </tr>
                        <tr>
                            <td>Tidak kawin 3 tanggungan (TK/3)</td>
                            <td style="text-align: right;">67.500.000</td>
                        </tr>
                        <tr>
                            <td>Kawin 1 tanggungan (K/1)</td>
                            <td style="text-align: right;">63.000.000</td>
                        </tr>
                        <tr>
                            <td>Kawin 2 tanggungan (K/2)</td>
                            <td style="text-align: right;">67.500.000</td>
                        </tr>

                        <!-- Kategori C -->
                        <tr>
                            <td rowspan="1" style="text-align: center; vertical-align: middle;">TER Kategori C</td>
                            <td>Kawin 3 tanggungan (K/3)</td>
                            <td style="text-align: right;">72.000.000</td>
                        </tr>
                    </tbody>
                </table>
                <p style="font-size: 0.85rem; color: gray;">Sumber: Klikpajak. (2025, 2 Januari). <i>PPh 21 Terbaru 2025 dan Contoh Perhitungan 21 Tarif TER</i></p>
            </div>
        </div>
    </div>
</div>

        <!-- Tabel Tarif PPh 21 (2025) -->
  <div class="col-lg-6 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background-color: rgb(95, 93, 61); border-color: rgb(99, 97, 63);">
            <h6 class="m-0 font-weight-bold" style="color: white;">Tarif Lapisan Pajak Penghasilan Pasal 21 (2025)</h6>
        </div>
        
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead style="background-color: rgb(223, 220, 185); color: rgb(80, 78, 51);">
                    <tr>
                        <th style="text-align: center; vertical-align: middle;">Lapisan Tarif</th>
                        <th style="text-align: center; vertical-align: middle;">PKP (UU PPh 36/2008)</th>
                        <th style="text-align: center; vertical-align: middle;">Tarif Pajak</th>
                        <th style="text-align: center; vertical-align: middle;">PKP (UU HPP 7/2021)</th>
                        <th style="text-align: center; vertical-align: middle;">Tarif Pajak</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">I</td>
                        <td>Rp 0 – 50 juta</td>
                        <td style="text-align: center;">5%</td>
                        <td>Rp 0 – 60 juta</td>
                        <td style="text-align: center;">5%</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">II</td>
                        <td>&gt; Rp 50 – 250 juta</td>
                        <td style="text-align: center;">15%</td>
                        <td>&gt; Rp 60 – 250 juta</td>
                        <td style="text-align: center;">15%</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">III</td>
                        <td>&gt; Rp 250 – 500 juta</td>
                        <td style="text-align: center;">25%</td>
                        <td>&gt; Rp 250 – 500 juta</td>
                        <td style="text-align: center;">25%</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">IV</td>
                        <td>&gt; Rp 500 juta</td>
                        <td style="text-align: center;">30%</td>
                        <td>Rp 500 juta – 5 miliar</td>
                        <td style="text-align: center;">30%</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">V</td>
                        <td>-</td>
                        <td style="text-align: center;">-</td>
                        <td>&gt; Rp 5 miliar</td>
                        <td style="text-align: center;">35%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


        @endsection