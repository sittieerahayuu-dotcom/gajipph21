<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Laporan Perhitungan Gaji</title>
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 10px;
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
            word-wrap: break-word;
        }

        th {
            background-color: rgb(80, 78, 51);
            color: white;
            font-weight: 600;
            font-size: 11px;
            text-align: center;
        }

        /* Lebar kolom & alignment khusus */
        th:nth-child(1),
        td:nth-child(1) {
            width: 2%;
            /* No */
            text-align: center;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 5%;
            /* Tanggal */
            text-align: center;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 6%;
            /* NIK */
            text-align: center;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 7%;
            /* Nama */
            text-align: center;
            padding-left: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th:nth-child(5),
        /* Jabatan */
        td:nth-child(5) {
            width: 6%;
            text-align: center;
            padding-left: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        th:nth-child(6),
        /* Gapok */
        td:nth-child(6) {
            width: 8%;
            text-align: center;
            padding-left: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th:nth-child(n+7):nth-child(-n+14),
        /* baris 7-14 */
        td:nth-child(n+7):nth-child(-n+14) {
            width: 7%;
            text-align: center;
            white-space: nowrap;
            padding-right: 8px;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }

        th:nth-child(15),
        td:nth-child(15) {
            width: 8%;
            text-align: center;
            padding-left: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th:nth-child(16),
        td:nth-child(16) {
            width: 8%;
            text-align: center;
            padding-left: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th:nth-child(17),
        td:nth-child(17) {
            width: 8%;
            text-align: center;
            padding-left: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .currency {
            /* Ini bisa tetap ada atau dihapus karena sudah di th/td di atas */
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
        <p><strong>Perihal:</strong> Laporan Perhitungan Gaji</p>
        <p><strong>Periode:</strong>
            Bulan
            @if($bulan)
            {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F') }}
            @else
            Januari–Desember
            @endif
            {{ $tahun ?? '' }}
        </p>
        </p>
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
        <h3>LAPORAN PERHITUNGAN GAJI BERSIH</h3>
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
                <th>Tj.<br>Makan</th>
                <th>Tj.<br>Transport</th>
                <th>Tj.<br>BPJS</th>
                <th>Tj.<br>JKM</th>
                <th>Tj.<br>JKK</th>
                <th>Pot.<br>Kehadiran</th>
                <th>Pot.<br>BPJS 1%</th>
                <th>Pot.<br>PPh21</th>
                <th>Total<br>Pendapatan</th>
                <th>Total<br>Potongan</th>
                <th>Gaji<br>Bersih</th>
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
                <td class="currency">Rp {{ number_format($item->tunjangan_makan, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->tunjangan_transportasi, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->tunjangan_bpjs, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->tunjangan_jkm, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->tunjangan_jkk, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->potongan_kehadiran, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->potongan_bpjs, 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($item->potongan_pph21, 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold;">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold; color: red;">Rp {{ number_format($item->total_potongan, 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold; color: green;">Rp {{ number_format($item->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f2eaa5;">
                <td colspan="5" style="text-align: center;">TOTAL</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('gaji_pokok'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('tunjangan_makan'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('tunjangan_transportasi'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('tunjangan_bpjs'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('tunjangan_jkm'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('tunjangan_jkk'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('potongan_kehadiran'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('potongan_bpjs'), 0, ',', '.') }}</td>
                <td class="currency">Rp {{ number_format($perhitungans->sum('potongan_pph21'), 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold;">Rp {{ number_format($perhitungans->sum('total_pendapatan'), 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold; color: red;">Rp {{ number_format($perhitungans->sum('total_potongan'), 0, ',', '.') }}</td>
                <td class="currency" style="font-weight: bold; color: green;">Rp {{ number_format($perhitungans->sum('gaji_bersih'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <div>
            <br /><br />
            <p>Staff Penggajian</p>
            <br /><br /><br /><br />
            <p><strong>Siti Nurindah Sari</strong></p>
        </div>
        <div>
            <p>Cimahi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Direktur CV. Lisa Wangi</p>
            <br /><br /><br /><br />
            <p><strong>Yasmine Assegaf</strong></p>
        </div>
    </div>
</body>

</html>