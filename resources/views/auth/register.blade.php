<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="d-flex vh-100 align-items-center justify-content-center">
    <div class="card shadow-sm" style="width:100%; max-width:420px; border-radius:.75rem;">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4">User Mangement</h3>
            <h5 class="card-title text-center mb-3 text-muted">Daftar</h5>

            <form action="{{ route('register') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                    <a href="{{ route('login.form') }}" class="btn btn-outline-secondary">Masuk</a>
                </div>

                <div class="text-center mt-3">
                    <a href="https://rechmand.id">www.rechmand.id</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card { background: #fff; }
    .form-control:focus { box-shadow: none; border-color: #86b7fe; }
</style>
</body>
</html>
