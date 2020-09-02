<html>
 <head>
   <meta http-http-equiv="Connect-type" content="text/html" ; charset="utf-8" />
 <title>會員登入系統</title>
 </head>
 <body>
       會員登入     <a href=/todolist>回主頁  </a>
      <a href=/RegisterPage>註冊</a><br>
  <form  action="/api/GetToken" method="post">
      {{ csrf_field() }}
    <input type="text" name="username" placeholder="輸入帳號"><br>
    <input type="password" name="passwd" placeholder="輸入密碼"><br>
    <input type="submit" value="登入" >
  </form>
 </body>
 </html>
