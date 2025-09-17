<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Perhitungan PPh 21</title>
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 5px;
        }

        .kop-surat {
            position: relative;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-family: 'Times New Roman', serif;
            text-align: center;
        }

        .kop-surat .logo {
            position: absolute;
            left: 150px;
            /* Geser dari kiri */
            top: -8px;
            /* Naikkan posisi logo */
            width: 100px;
            height: 90px;
        }

        .kop-surat .info {
            display: inline-block;
            width: 100%;
        }

        .kop-surat .info h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .kop-surat .info h2 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }

        .kop-surat .info p {
            margin: 3px 0 0 0;
            font-size: 16px;
        }

        .header {
            margin-bottom: 20px;
            text-align: left;
        }

        .header h3 {
            font-family: 'Times New Roman', serif;
            margin: 0 0 10px 0;
            text-align: center;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }

        th {
            background-color: rgb(80, 78, 51);
            color: white;
            font-weight: 600;
            font-size: 11px;
            text-align: center;
        }

        td {
            text-align: center;
        }

        td:nth-child(4),
        td:nth-child(5) {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-left: 6px;
            text-align: left;
        }

        /* Lebar kolom */
        table th:nth-child(1),
        table td:nth-child(1) {
            width: 2%;
            text-align: center;
            /* No */
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 4%;
            text-align: center;
            /* Tanggal */
        }

        table th:nth-child(3),
        table td:nth-child(3) {
            width: 5%;
            text-align: center;
            /* NIK */
        }

        table th:nth-child(4),
        table td:nth-child(4) {
            width: 7%;
            text-align: center;
            /* Nama */
        }

        table th:nth-child(5),
        table td:nth-child(5) {
            width: 6%;
            text-align: center;
            /* Jabatan */
        }

        table th:nth-child(6),
        table td:nth-child(6) {
            width: 10%;
            text-align: center;
            /* Gaji Pokok */
        }

        table th:nth-child(7),
        table td:nth-child(7) {
            width: 9%;
            text-align: center;
            /* total Tunjangan */
        }

        table th:nth-child(8),
        table td:nth-child(8) {
            width: 9%;
            text-align: center;
            /* Potongan Kehadiran */
        }

        table th:nth-child(9),
        table td:nth-child(9) {
            width: 10%;
            text-align: center;
            /* Penghasilan Bruto */
        }

        table th:nth-child(10),
        table td:nth-child(10) {
            width: 8%;
            text-align: center;
            /* Biaya Jabatan */
        }

        table th:nth-child(11),
        table td:nth-child(11) {
            width: 8%;
            text-align: center;
            /* iuran BPJS 1% */
        }

        table th:nth-child(12),
        table td:nth-child(12) {
            width: 10%;
            text-align: center;
            /* Netto bulan */
        }

        table th:nth-child(13),
        table td:nth-child(13) {
            width: 11%;
            text-align: center;
            /* Netto tahun*/
        }

        table th:nth-child(14),
        table td:nth-child(14) {
            width: 10%;
            text-align: center;
            /* PTKP*/
        }

        table th:nth-child(15),
        table td:nth-child(15) {
            width: 10%;
            text-align: center;
            /* PKP */
        }

        table th:nth-child(16),
        table td:nth-child(16) {
            width: 9%;
            text-align: center;
            /* PPH 21 Tahun */
        }

        table th:nth-child(17),
        table td:nth-child(17) {
            width: 8%;
            text-align: center;
            /* PPH 21 Bulan */
        }


        /* Currency alignment */
        .currency {
            text-align: right;
            white-space: nowrap;
            padding-right: 8px;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }

        .signature {
            display: table;
            width: 100%;
            margin-top: 50px;
        }

        .signature div {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="{{ public_path('images/lisawb.jpeg') }}" alt="Logo" class="logo" />
        <div class="info">
            <h1>CV. LISA WANGI BANDUNG</h1>
            <h2>Distributor Bibit & Botol Parfum Berkualitas</h2>
            <p>Jl.BKR No.15 D-E, Cijagra, Kec. Lengkong, Kota Bandung, Jawa Barat 40265</p>
            <p>Telepon: (022) 7318697 | IG: @lisawangibdg</p>
        </div>
    </div>

    <div class="header">
        <p><strong>Perihal:</strong> Laporan Perhitungan PPh 21</p>
        <p><strong>Periode:</strong>
            Bulan
            @if($bulan)
            {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F') }}
            @else
            Januari–Desember
            @endif
            {{ $tahun ?? '' }}
        <p><strong>Karyawan:</strong>
            @if(request('karyawan_id'))
            @php
            $selectedKaryawan = $karyawans->firstWhere('id', request('karyawan_id'));
            @endphp

            @if($selectedKaryawan)
            {{ $selectedKaryawan->no_induk }} - {{ $selectedKaryawan->nama_karyawan }}
            @else
            Karyawan tidak ditemukan
            @endif
            @else
            Semua Karyawan
            @endif
        </p>
        <h3>LAPORAN PERHITUNGAN PPH 21</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl</th>
                <th>NIK/NPWP</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Gaji<br>Pokok</th>
                <th>Total<br>Tunjangan</th>
                <th>Potongan.<br>Kehadiran</th>
                <th>Penghasilan<br>Bruto</th>
                <th>Biaya<br>Jabatan</th>
                <th>Iuran<br>BPJS 1%</th>
                <th>Penghasilan Netto<br>/Bulan</th>
                <th>Penghasilan Netto<br>/Tahun</th>
                <th>PTKP</th>
                <th>PKP</th>
                <th>PPh21<br>/Tahun</th>
                <th>PPh21<br>/Bulan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perhitungans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->nik }}</td>
                <td title="{{ $item->nama_karyawan }}">{{ $item->nama_karyawan }}</td>
                <td style="word-break: break-word; white-space: normal;" title="{{ $item->jabatan }}">
                    {{ $item->jabatan }}
                </td>
                <td class="currency">Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                <td class="currency">
                    Rp {{ number_format(
                        $item->tunjangan_makan + 
                        $item->tunjangan_transportasi + 
                        $item->tunjangan_bpjs + 
                        $item->tunjangan_jkm + 
                        $item->tunjangan_jkk, 
                        0, ',', '.') 
                    }}
                </td>
                <td class="currency" style="color: red;">Rp {{ number_format($item->potongan_kehadiran, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->penghasilan_bruto, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->biaya_jabatan, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->iuran_bpjs, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->netto_per_bulan, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->netto_per_tahun, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->ptkp, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->pkp, 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold;">Rp {{ number_format($item->pph21_setahun, 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold;">Rp {{ number_format($item->pph21_sebulan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if ($perhitungans->count())
            <tr style="font-weight: bold; background-color: #f2eaa5;">
                <td colspan="5" style="text-align:center;">TOTAL</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('gaji_pokok'), 0, ',', '.') }}</td>
                <td class="currency">
                    Rp {{ number_format(
            $perhitungans->sum('tunjangan_makan') + 
            $perhitungans->sum('tunjangan_transportasi') + 
            $perhitungans->sum('tunjangan_bpjs') + 
            $perhitungans->sum('tunjangan_jkm') + 
            $perhitungans->sum('tunjangan_jkk'),
            0, ',', '.')
        }}
                </td>
                <td class="currency" style="color:red;">
                    Rp {{ number_format($perhitungans->sum('potongan_kehadiran'), 0, ',', '.') }}
                </td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('penghasilan_bruto'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('biaya_jabatan'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('iuran_bpjs'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('netto_per_bulan'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('netto_per_tahun'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('ptkp'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('pkp'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('pph21_setahun'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('pph21_sebulan'), 0, ',', '.') }}</td>
            </tr>
            @endif

        </tbody>
    </table>

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