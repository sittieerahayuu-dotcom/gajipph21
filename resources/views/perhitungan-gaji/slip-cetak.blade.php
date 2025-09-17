<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Slip Gaji</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
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
            left: 10px;
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
            font-size: 20px;
            font-weight: bold;
        }

        .kop-surat .info h2 {
            margin: 5px 0 0 0;
            font-size: 12px;
            font-weight: normal;
        }

        .kop-surat .info p {
            margin: 3px 0 0 0;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3 {
            margin: 0;
            font-family: 'Times New Roman', serif;
            font-size: 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            /* single column for all left aligned */
            margin-bottom: 20px;
            font-size: 12px;
            gap: 5px 0;
            align-items: start;
        }

        .info-grid p {
            margin: 7;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #000;
        }

        th {
            background-color: rgb(80, 78, 51);
            color: #fff;
            text-align: left;
        }

        .currency {
            text-align: right;
            white-space: nowrap;
        }

        .total {
            font-weight: bold;
            background-color: #eee;
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
            <p>Jl. BKR No.15 D-E, Cijagra, Kec. Lengkong, Kota Bandung, Jawa Barat 40265</p>
            <p>Telepon: (022) 7318697 | IG: @lisawangibdg</p>
        </div>
    </div>

    <div class="header">
        <h3>SLIP GAJI KARYAWAN</h3>
        <br>
        <h3>Periode - {{ \Carbon\Carbon::parse($gaji->tanggal)->translatedFormat('F Y') }}</h3>
    </div>

    <!-- INFO GRID -->
    <div class="info" style="font-family: Arial, sans-serif; font-size: 12px;">
    <p style="margin: 5px 0;">
        <strong>NO. INDUK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{ $gaji->karyawan->no_induk }}
    </p>
    <p style="margin: 5px 0;">
        <strong>NAMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{ $gaji->karyawan->nama_karyawan }}
    </p>
    <p style="margin: 5px 0;">
        <strong>NIK/NPWP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{ $gaji->karyawan->nik }}
    </p>
    <p style="margin: 5px 0;">
        <strong>JABATAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{ $gaji->karyawan->jabatan->nama_jabatan }}
    </p>
    <p style="margin: 5px 0;">
        <strong>STATUS PTKP &nbsp;&nbsp; :</strong> {{ $gaji->karyawan->kode_ptkp }}
    </p>
</div>


    <br>
    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="currency">Jumlah (Rp)</th>
            </tr>
        </thead>

        <!-- Penghasilan -->
        <tbody>
            <tr>
                <td colspan="2" style="text-align: center;"><strong>Penghasilan (+)</strong></td>
            </tr>
            <tr>
                <td>Gaji Pokok</td>
                <td class="currency">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Makan</td>
                <td class="currency">Rp {{ number_format($gaji->tunjangan_makan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan Transportasi</td>
                <td class="currency">Rp {{ number_format($gaji->tunjangan_transportasi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan BPJS (4%)</td>
                <td class="currency">Rp {{ number_format($gaji->tunjangan_bpjs, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan JKK</td>
                <td class="currency">Rp {{ number_format($gaji->tunjangan_jkk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan JKM</td>
                <td class="currency">Rp {{ number_format($gaji->tunjangan_jkm, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total Pendapatan Gaji Kotor</td>
                <td class="currency">Rp {{ number_format($gaji->total_pendapatan, 0, ',', '.') }}</td>
            </tr>

            <!-- Pengurangan -->
            <tr>
                <td colspan="2" style="text-align: center;"><strong>Potongan (-)</strong></td>
            </tr>
            <tr>
                <td>Potongan Kehadiran</td>
                <td class="currency">Rp {{ number_format($gaji->potongan_kehadiran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Potongan BPJS 1%</td>
                <td class="currency">Rp {{ number_format($gaji->potongan_bpjs, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Potongan PPh21</td>
                <td class="currency">Rp {{ number_format($gaji->potongan_pph21, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>Total Potongan</td>
                <td class="currency" style="color: red;">Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
            </tr>

            <!-- Gaji Bersih -->
            <tr class="total">
                <td>Total Pendapatan Gaji Bersih</td>
                <td class="currency" style="color: green;">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td colspan="2" style="font-style: italic; text-align: center;">
                    Terbilang:
                    {{ ucwords((new \NumberFormatter('id_ID', \NumberFormatter::SPELLOUT))->format($gaji->gaji_bersih)) }} Rupiah
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <div>
            <br /><br />
            <p>Staff Payroll</p>
            <br /><br /><br /><br />
            <p><strong>Siti Nurindah Sari</strong></p>
        </div>
        <div>
            <p>Cimahi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Di Terima oleh</p>
            <br /><br /><br /><br />
            <p><strong>{{ $gaji->karyawan->nama_karyawan }}</strong></p>
        </div>
    </div>

</body>

</html>