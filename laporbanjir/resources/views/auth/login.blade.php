<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Petugas | LaporBanjir</title>
    <link rel="stylesheet" href="{{ asset('css/laporbanjir.css') }}">
</head>
<body>
    <main class="container narrow">
        <section class="panel login-panel">
            <a class="brand" href="{{ route('laporan.create') }}"><span class="brand-mark">⌁</span> LaporBanjir</a>
            <p class="eyebrow" style="margin-top:30px">Area petugas</p>
            <h1 style="font-size:30px">Selamat datang</h1>
            <p class="intro">Masuk untuk mengelola laporan banjir warga.</p>
            <form action="{{ route('login.store') }}" method="POST">
                @csrf
                <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>@error('email')<p class="error" role="alert">{{ $message }}</p>@enderror</div>
                <div class="field"><label for="password">Kata sandi</label><input id="password" type="password" name="password" required>@error('password')<p class="error" role="alert">{{ $message }}</p>@enderror</div>
                <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <div class="actions"><button type="submit">Masuk ke dashboard</button></div>
            </form>
            <p class="hint" style="margin-top:22px"><a href="{{ route('laporan.create') }}">← Kembali ke form laporan</a></p>
        </section>
    </main>
</body>
</html>
