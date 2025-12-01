<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <link rel="icon" href="assets/icon.png" />
  <link rel="stylesheet" href="css/login2.css" />
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
		<li><a href="index.php">Home</a></li>
	    </ul>
        </nav>
	</header>
	<main>
	  <div class="center">
	    <div class="form-login">
		 <h3>LOGIN</h3>
		 <form action="login-proses.php" method="post">
		   <input class="input" type="text" name="username"
			    placeholder="Username" />
	         <input class="input" type="password" name="password"
			    placeholder="Password" />
		   <button type="submit" class="btn_login" name="login"  
                      id="login"> Login
		   </button>
		 </form>
		 <a href="Register.php" class="link-register">Registers</a>
	    </div>
	  </div>
	</main>
   </div>
</body>
</html>
