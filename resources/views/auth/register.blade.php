<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <link rel="icon" href="{{ asset('assets/icon.png') }}" />
  <link rel="stylesheet" href="{{ asset('css/login2.css') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Roboto:wght@500;700&display=swap"
    rel="stylesheet"/>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <div class="logo">
          <img src="{{ asset('assets/logo.png') }}" alt="" />
        </div>
        <input type="checkbox" id="click" />
        <label for="click" class="menu-btn">
          <i class="fas fa-bars"></i>
        </label>
    </header>
    <main>
      <div class="center">
        <div class="form-login">
          <h3>Register</h3>

          <form action="{{ route('register') }}" method="post">
            @csrf

            <input class="input" 
                   type="text" 
                   name="nama_pembeli" 
                   placeholder="Nama Lengkap" 
                   value="{{ old('nama_pembeli') }}" required />
            @error('nama_pembeli')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <input class="input" 
                   type="text" 
                   name="username"
                   placeholder="Username" 
                   value="{{ old('username') }}" required />
            @error('username')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <input class="input" 
                   type="password" 
                   name="password"
                   placeholder="Password" required />
            @error('password')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <input class="input" 
                   type="password" 
                   name="password_confirmation"
                   placeholder="Confirm Password" required />

            <input class="input" 
                   type="text" 
                   name="alamat"
                   placeholder="Alamat" 
                   value="{{ old('alamat') }}" required />
            @error('alamat')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <input class="input" 
                   type="email" 
                   name="email"
                   placeholder="Email" 
                   value="{{ old('email') }}" required />
            @error('email')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <select class="input" 
                    name="jenis_kelamin" required>
              <option value="" disabled selected>Jenis Kelamin</option>
              <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
              <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <input class="input" 
                   type="date" 
                   name="tanggal_lahir"
                   value="{{ old('tanggal_lahir') }}"
                   required />
            @error('tanggal_lahir')
              <span class="error-text">{{ $message }}</span>
            @enderror

            <button type="submit" class="btn_login" name="register" id="register">
              Register
            </button>

          </form>

        </div>
      </div>
    </main>
  </div>
</body>
</html>