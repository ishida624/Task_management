<html>
<head>
  <meta http-http-equiv="Connect-type" content="text/html" ; charset="utf-8" />
<title>laravel todolist</title>
</head>
<body>
        <form id="form1" action="{{ url("/delete") }}" method="post" >
        {{ csrf_field() }}
         @method('DELETE')
        <input type = "hidden" name="id" value= "<?php echo "$id"; ?>">
		</form>
        <script>
        form1.submit();
</script>
    </body>
    </html>
