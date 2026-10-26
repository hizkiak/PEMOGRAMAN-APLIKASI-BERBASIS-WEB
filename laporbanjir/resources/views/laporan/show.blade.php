<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Detail Laporan | LaporBanjir</title><link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}"></head>
<body>
<main class="container narrow">
    <div class="topbar"><a class="brand" href="{{ route('laporan.index') }}"><span class="brand-mark">⌁</span> LaporBanjir</a><a class="btn secondary" href="{{ route('laporan.index') }}">← Daftar laporan</a></div>
    <p class="eyebrow">Rincian laporan · {{ $laporan->status }}</p><h1>{{ $laporan->lokasi }}</h1><p class="intro">Dilaporkan oleh {{ $laporan->nama_pelapor }}.</p>
    <section class="panel">
        <div class="detail-grid">
            <div class="detail-item"><small>Nama pelapor</small><strong>{{ $laporan->nama_pelapor }}</strong></div>
            <div class="detail-item"><small>Nomor HP</small><strong>{{ $laporan->no_hp }}</strong></div>
            <div class="detail-item"><small>Kecamatan</small><strong>{{ $laporan->kecamatan }}</strong></div>
            <div class="detail-item"><small>Tinggi air</small><strong>{{ $laporan->tinggi_air }} cm</strong></div>
            <div class="detail-item full"><small>Keterangan</small><strong>{{ $laporan->keterangan }}</strong></div>
            <div class="detail-item full"><small>Status penanganan</small><span class="badge {{ strtolower($laporan->status) }}">{{ $laporan->status }}</span></div>
            <div class="detail-item full"><small>Foto bukti</small><img class="evidence" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto bukti banjir"></div>
        </div>
        <div class="actions"><a class="btn" href="{{ route('laporan.edit', $laporan) }}">Edit laporan</a><a class="btn secondary" href="{{ route('laporan.index') }}">Kembali</a></div>
    </section>
</main>
</body>
</html>
