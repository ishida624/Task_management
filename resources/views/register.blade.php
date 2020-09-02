<html>
<head>
  <meta http-http-equiv="Connect-type" content="text/html" ; charset="utf-8" />
<title>會員註冊系統</title>
</head>
<body>
      會員註冊     <a href=/todolist>回主頁</a><br>
 <form  action="/api/registeredController" method="post">
     {{ csrf_field() }}
   <input type="text" name="username" placeholder="輸入帳號"><br>
   <input type="password" name="passwd" placeholder="輸入密碼"><br>
   <input type="password" name="checkpasswd" placeholder="確認密碼"><br>
   <input type="hidden" value="click" name="click" >
   <input type="submit" value="註冊" >
 </form>
 @if(session('success'))
    <h1>{{session('success')}}</h1>
@endif

</body>
</html>
