<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BAST - {{ $ticket->ticket_code }} - Diskominfo Kab. Jombang</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; margin: 40px; color: #000; }
        .kop-surat { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-text { text-align: center; flex: 1; }
        .kop-text h3 { font-size: 14pt; margin: 0; text-transform: uppercase; font-weight: bold; }
        .kop-text h2 { font-size: 16pt; margin: 2px 0; text-transform: uppercase; font-weight: bold; }
        .kop-text p { font-size: 10pt; margin: 0; font-style: italic; }
        .title { text-align: center; margin-bottom: 25px; }
        .title h4 { text-decoration: underline; font-size: 13pt; margin: 0; text-transform: uppercase; font-weight: bold; }
        .title p { margin: 2px 0 0 0; font-size: 11pt; }
        table.meta { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        table.meta td { padding: 5px; vertical-align: top; font-size: 11pt; }
        .box-hasil { border: 1px solid #000; padding: 12px; margin: 15px 0; background: #f9f9f9; font-family: monospace; font-size: 10pt; }
        .ttd-container { display: flex; justify-content: space-between; margin-top: 50px; page-break-inside: avoid; }
        .ttd-box { text-align: center; width: 45%; }
        .ttd-space { height: 70px; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak / Kembali (Hidden when printing) -->
    <div class="no-print" style="margin-bottom: 20px; background: #f1f5f9; padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <button onclick="window.close()" style="padding: 8px 16px; font-weight: bold; cursor: pointer;">← Kembali</button>
        <button onclick="window.print()" style="padding: 8px 20px; background: #0f2c59; color: #fff; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">🖨️ Cetak Berita Acara (PDF)</button>
    </div>

    <!-- Kop Surat Pemkab Jombang -->
    <div class="kop-surat">
        <div style="width: 70px; text-align: center;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f6/Lambang_Kabupaten_Jombang.png" alt="Logo Jombang" style="width: 60px;">
        </div>
        <div class="kop-text">
            <h3>PEMERINTAH KABUPATEN JOMBANG</h3>
            <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
            <p>Jl. KH. Wahid Hasyim No. 137, Jombang, Jawa Timur | Email: diskominfo@jombangkab.go.id</p>
        </div>
    </div>

    <!-- Judul Dokumen BAST -->
    <div class="title">
        <h4>BERITA ACARA SERAH TERIMA (BAST) LAYANAN APTIKA</h4>
        <p>Nomor Tiket: <strong>{{ $ticket->ticket_code }}</strong></p>
    </div>

    <p>Pada hari ini, tanggal <strong>{{ date('d F Y', strtotime($ticket->updated_at ?? $ticket->created_at)) }}</strong>, telah diselesaikan pengerjaan permohonan layanan Sistem Pemerintahan Berbasis Elektronik (SPBE) Bidang Aplikasi dan Informatika (APTIKA) Diskominfo Kabupaten Jombang dengan rincian sebagai berikut:</p>

    <!-- Tabel Rincian -->
    <table class="meta">
        <tr>
            <td width="30%"><strong>Instansi Pemohon (OPD)</strong></td>
            <td width="3%">:</td>
            <td>{{ $ticket->opd_name }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Layanan</strong></td>
            <td>:</td>
            <td>{{ $ticket->service_name }}</td>
        </tr>
        <tr>
            <td><strong>Detail / Usulan Spesifikasi</strong></td>
            <td>:</td>
            <td>{{ $ticket->detail_target }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Pengajuan</strong></td>
            <td>:</td>
            <td>{{ date('d-m-Y H:i', strtotime($ticket->created_at)) }} WIB</td>
        </tr>
        <tr>
            <td><strong>Petugas Teknisi PIC</strong></td>
            <td>:</td>
            <td>{{ $ticket->assigned_to }}</td>
        </tr>
        <tr>
            <td><strong>Status Pekerjaan</strong></td>
            <td>:</td>
            <td><strong style="color: green;">SELESAI & AKTIF (BAST TERBIT)</strong></td>
        </tr>
    </table>

    <p><strong>Hasil Pengerjaan Teknis:</strong></p>
    <div class="box-hasil">
        {{ $ticket->tech_result ?? 'Subdomain & Hosting VPS telah aktif dan lulus verifikasi keamanan sistem APTIKA Diskominfo Jombang.' }}
    </div>

    <p style="margin-top: 20px;">Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>

    <!-- Tanda Tangan Resmi -->
    <div class="ttd-container">
        <div class="ttd-box">
            <p>Pihak Pemohon (OPD)</p>
            <p><strong>{{ $ticket->opd_name }}</strong></p>
            <div class="ttd-space"></div>
            <p><u>( .................................................. )</u></p>
            <p>NIP. ..............................................</p>
        </div>
        <div class="ttd-box">
            <p>Jombang, {{ date('d F Y') }}</p>
            <p><strong>Diskominfo Kab. Jombang (APTIKA)</strong></p>
            <div class="ttd-space"></div>
            @if($teknisi)
                <p><u><strong>{{ $teknisi->name }}</strong></u></p>
                <p>NIP. {{ $teknisi->nip ?? '....................................' }}</p>
            @else
                <p><u><strong>{{ $ticket->assigned_to }}</strong></u></p>
                <p>NIP. ..............................................</p>
            @endif
        </div>
    </div>

    <script>
        // Auto open print dialog when opened
        window.onload = function() {
            // Optional: window.print();
        };
    </script>
</body>
</html>
