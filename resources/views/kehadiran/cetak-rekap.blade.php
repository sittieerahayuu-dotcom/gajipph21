<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran Karyawan</title>
    <style>
        @page { size: landscape; margin: 10mm; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 10px; }

        .kop-surat {
            position: relative;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-family: 'Times New Roman', serif;
            text-align: center;
        }
        .kop-surat .logo {
            position: absolute; left: 150px; top: -8px;
            width: 100px; height: 90px;
        }
        .kop-surat .info { display: inline-block; width: 100%; }
        .kop-surat .info h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .kop-surat .info h2 { margin: 5px 0 0 0; font-size: 16px; font-weight: normal; }
        .kop-surat .info p { margin: 3px 0 0 0; font-size: 14px; }

        .header { margin-bottom: 20px; text-align: left; }
        .header h3 { margin: 0; text-align: center; font-family: 'Times New Roman', serif; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        th { background-color: rgb(80, 78, 51); color: white; }
        tfoot tr td { font-weight: bold; background-color: #f2eaa5; }

        .signature { display: table; width: 100%; margin-top: 50px; }
        .signature div { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
    </style>
</head>

<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="{{ public_path('images/lisawb.jpeg') }}" alt="Logo" class="logo" />
        <div class="info">
            <h1>CV. LISA WANGI BANDUNG</h1>
            <h2>Distributor Bibit & Botol Parfum Berkualitas</h2>
            <p>Jl. BKR No.15 D-E, Cijagra, Kec. Lengkong, Kota Bandung, Jawa Barat 40265</p>
            <p>Telepon: (022) 7318697 | IG: @lisawangibdg</p>
        </div>
    </div>

    <!-- Header Laporan -->
    <div class="header">
        <p><strong>Perihal:</strong> Rekap Kehadiran</p>
        <p><strong>Periode:</strong>
            @if($bulan)
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F') }}
            @else
                Januari - Desember
            @endif
            {{ $tahun ?? '' }}
        </p>
        <p><strong>Karyawan:</strong>
            @if(isset($selectedKaryawan) && $selectedKaryawan)
                {{ $selectedKaryawan->no_induk ?? $selectedKaryawan->nik }} - {{ $selectedKaryawan->nama_karyawan }}
            @else
                Semua Karyawan
            @endif
        </p>
    </div>

    <div class="header">
        <h3>REKAP KEHADIRAN KARYAWAN</h3>
    </div>

    <!-- Tabel Rekap -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Masuk</th>
                <th>Alpha</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Cuti</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalMasuk = $totalAlpha = $totalSakit = $totalIzin = $totalCuti = 0;
            @endphp
            @foreach ($rekap as $index => $k)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $k->nik }}</td>
                    <td style="text-align: left;">{{ $k->nama_karyawan }}</td>
                    <td>{{ $k->nama_jabatan }}</td>
                    <td>{{ $k->jumlah_masuk }}</td>
                    <td>{{ $k->jumlah_alpha }}</td>
                    <td>{{ $k->jumlah_sakit }}</td>
                    <td>{{ $k->jumlah_izin }}</td>
                    <td>{{ $k->jumlah_cuti }}</td>
                </tr>
                @php
                    $totalMasuk += $k->jumlah_masuk;
                    $totalAlpha += $k->jumlah_alpha;
                    $totalSakit += $k->jumlah_sakit;
                    $totalIzin  += $k->jumlah_izin;
                    $totalCuti  += $k->jumlah_cuti;
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total</td>
                <td>{{ $totalMasuk }}</td>
                <td>{{ $totalAlpha }}</td>
                <td>{{ $totalSakit }}</td>
                <td>{{ $totalIzin }}</td>
                <td>{{ $totalCuti }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature">
        <div>
            <br><br>
            <p>Staff Penggajian</p>
            <br><br><br><br>
            <p><strong>Siti Nurindah Sari</strong></p>
        </div>
        <div>
            <p>Cimahi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Direktur CV. Lisa Wangi</p>
            <br><br><br><br>
            <p><strong>Yasmine Assegaf</strong></p>
        </div>
    </div>
</body>
</html>
