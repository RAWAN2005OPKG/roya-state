
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>تسجيل الدخول - منصة الراية</title>
  <link rel="stylesheet" href="{{asset('dashboard/css/login.css')}}" />

</head>
<body>
    
  <div class="background"></div>

  <div class="container">
    <div class="logo">
      <img src="تنزيل.png" alt="شعار منصة الراية" />
    </div>

    <h1 class="welcome">مرحباً بك في منصة الراية</h1>


    <form name="loginForm" action="" method="post" class="login-form" onsubmit="return validateForm()">
     <input type="email" name="email" id="email"placeholder="gmail" required >
      <input type="password" name="password" placeholder="password" required />
      <button type="submit">تسجيل الدخول</button>
    </form>

  </div>
      <script src="login.js"></script>

</body>
</html>
