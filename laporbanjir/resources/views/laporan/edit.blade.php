<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Laporan | LaporBanjir</title><link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}"></head>
<body>
<main class="container narrow">
    <div class="topbar"><a class="brand" href="{{ route('laporan.index') }}"><span class="brand-mark">⌁</span> LaporBanjir</a><a class="btn secondary" href="{{ route('laporan.index') }}">← Daftar laporan</a></div>
    <p class="eyebrow">Panel petugas</p><h1>Edit laporan</h1><p class="intro">Perbarui informasi laporan atau status penanganannya.</p>
    <section class="panel">
        <form action="{{ route('laporan.update', $laporan) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="field"><label for="nama_pelapor">Nama pelapor</label><input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor', $laporan->nama_pelapor) }}" required>@error('nama_pelapor')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="no_hp">Nomor HP</label><input id="no_hp" name="no_hp" value="{{ old('no_hp', $laporan->no_hp) }}" required>@error('no_hp')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label for="lokasi">Lokasi</label><input id="lokasi" name="lokasi" value="{{ old('lokasi', $laporan->lokasi) }}" required>@error('lokasi')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="kecamatan">Kecamatan</label><input id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $laporan->kecamatan) }}" required>@error('kecamatan')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="tinggi_air">Tinggi air (cm)</label><input id="tinggi_air" type="number" min="0" name="tinggi_air" value="{{ old('tinggi_air', $laporan->tinggi_air) }}" required>@error('tinggi_air')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label for="keterangan">Keterangan</label><textarea id="keterangan" name="keterangan" required>{{ old('keterangan', $laporan->keterangan) }}</textarea>@error('keterangan')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label>Foto saat ini</label><img class="evidence" src="{{ asset('storage/'.$laporan->foto) }}" alt="Foto bukti banjir"><label for="foto" style="margin-top:14px">Ganti foto (opsional)</label><input id="foto" type="file" name="foto" accept=".jpg,.jpeg,.png,image/jpeg,image/png"><p class="hint">JPG, JPEG, atau PNG · Maksimal 2 MB</p>@error('foto')<p class="error">{{ $message }}</p>@enderror</div>
                <div class="field full"><label for="status">Status penanganan</label><select id="status" name="status">@foreach (['Menunggu', 'Diproses', 'Selesai'] as $status)<option value="{{ $status }}" {{ old('status', $laporan->status) === $status ? 'selected' : '' }}>{{ $status }}</option>@endforeach</select>@error('status')<p class="error">{{ $message }}</p>@enderror</div>
            </div>
            <div class="actions"><button type="submit">Simpan perubahan</button><a class="btn secondary" href="{{ route('laporan.index') }}">Batal</a></div>
        </form>
    </section>
</main>
</body>
</html>
