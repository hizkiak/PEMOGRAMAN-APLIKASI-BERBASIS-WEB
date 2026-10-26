<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Laporan | LaporBanjir</title><link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}">
</head>
<body>
<main class="container">
    <div class="topbar">
        <a class="brand" href="{{ route('laporan.index') }}"><span class="brand-mark">⌁</span> LaporBanjir <span class="hide-mobile" style="font:500 13px 'DM Sans',Arial;color:#71847e">/ Panel petugas</span></a>
        <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn secondary" type="submit">Keluar</button></form>
    </div>
    <p class="eyebrow">Dashboard petugas</p>
    <h1>Data laporan banjir</h1>
    <p class="intro">Pantau laporan warga dan perbarui status penanganan di sini.</p>
    @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif

    <section class="panel">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Pelapor</th><th>Lokasi</th><th>Tinggi air</th><th>Foto</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse ($laporans as $laporan)
                    <tr>
                        <td><strong>{{ $laporan->nama_pelapor }}</strong><br><span class="hint">{{ $laporan->no_hp }}</span></td>
                        <td>{{ $laporan->lokasi }}<br><span class="hint">{{ $laporan->kecamatan }}</span></td>
                        <td>{{ $laporan->tinggi_air }} cm</td>
                        <td><img class="thumb" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto banjir"></td>
                        <td><span class="badge {{ strtolower($laporan->status) }}">{{ $laporan->status }}</span></td>
                        <td><div class="row-actions"><a href="{{ route('laporan.show', $laporan) }}">Detail</a><a href="{{ route('laporan.edit', $laporan) }}">Edit</a>
                            <form class="inline" action="{{ route('laporan.destroy', $laporan) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">@csrf @method('DELETE')<button class="danger" type="submit">Hapus</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:35px;color:#71847e">Belum ada laporan yang masuk.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $laporans->links() }}</div>
    </section>
</main>
</body>
</html>
