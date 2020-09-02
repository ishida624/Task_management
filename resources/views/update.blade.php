@extends('layouts.app')
@section('content')
<html>
<head>
  <meta http-http-equiv="Connect-type" content="text/html" ; charset="utf-8" />
<title>laravel todolist</title>
</head>
<body>
        <a href=/todolist>返回</a><br>

        <form action="{{ url("/update") }}" method="post" >
        {{ csrf_field() }}
         @method('PUT')
		<input type = "text" name="item" placeholder="修改內容">
        <input type = "hidden" name="no" value= "<?php echo "$id"; ?>">
		<input type="submit" value="確定修改" >
		<input type="reset">
		</form>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </body>
    </html>
@endsection
