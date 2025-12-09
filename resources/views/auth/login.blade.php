<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <link rel="icon" href="{{ asset('assets/icon.png') }}" />
  <link rel="stylesheet" href="{{ asset('css/login2.css') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Roboto:wght@500;700&display=swap"
    rel="stylesheet"
  />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
  <div class="container">
     <header>
      <nav>
        <input type="checkbox" id="click" />
        <label for="click" class="menu-btn">
           <i class="fas fa-bars"></i>
        </label>
        <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        </ul>
        </nav>
    </header>
    <main>
      <div class="center">
        <div class="form-login">
         <h3>LOGIN</h3>
         <form action="{{ route('login') }}" method="post">
           @csrf
           <input class="input" type="text" name="username"
                placeholder="Username" value="{{ old('username') }}" required/>
           @error('username')
             <span class="error-text">{{ $message }}</span>
           @enderror
         
           <input class="input" type="password" name="password"
                placeholder="Password" required/>
           @error('password')
             <span class="error-text">{{ $message }}</span>
           @enderror
           
           <button type="submit" class="btn_login" name="login"  
                  id="login"> Login
           </button>
         </form>
         <a href="{{ route('register') }}" class="link-register">Register</a>
        </div>
      </div>
    </main>
   </div>
</body>
</html>