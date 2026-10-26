<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Laporan Banjir | LaporBanjir</title>
    <link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}">
</head>
<body>
<main class="container narrow">
    <div class="topbar">
        <a class="brand" href="{{ route('laporan.create') }}"><span class="brand-mark">⌁</span> LaporBanjir</a>
        <a class="btn secondary" href="{{ route('login') }}">Login Petugas</a>
    </div>
    <p class="eyebrow">Layanan warga · BPBD Kabupaten Bandung</p>
    <h1>Laporkan kondisi banjir</h1>
    <p class="intro">Sampaikan informasi lokasi dan kondisi banjir. Foto membantu petugas memahami situasi dan menentukan penanganan.</p>

    @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif

    <section class="panel">
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="field"><label for="nama_pelapor">Nama pelapor</label><input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}" required>@error('nama_pelapor')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="no_hp">Nomor HP</label><input id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>@error('no_hp')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label for="lokasi">Alamat atau patokan lokasi</label><input id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required>@error('lokasi')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="kecamatan">Kecamatan</label><input id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" required>@error('kecamatan')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="tinggi_air">Tinggi air (cm)</label><input id="tinggi_air" type="number" min="0" name="tinggi_air" value="{{ old('tinggi_air') }}" required>@error('tinggi_air')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label for="keterangan">Keterangan kondisi</label><textarea id="keterangan" name="keterangan" required>{{ old('keterangan') }}</textarea>@error('keterangan')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label for="foto">Foto bukti</label><input id="foto" type="file" name="foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required><p class="hint">Format JPG, JPEG, atau PNG · Maksimal 2 MB</p>@error('foto')<p class="error">{{ $message }}</p>@enderror</div>
            </div>
            <div class="actions"><button type="submit">Kirim laporan <span aria-hidden="true">→</span></button></div>
        </form>
    </section>
</main>
</body>
</html>
