@extends('layouts.app')
@section('content')
<html>

<head>
    <meta http-http-equiv="Connect-type" content="text/html" ; charset="utf-8" />
    <title>laravel todolist</title>
    <style>
        .mid {
            margin: 0 auto;
            max-width: 1080px;
        }

        .form {
            text-align: center;
        }
    </style>
</head>

<body>
    <a href=/todolist>返回 </a> <br>

        <form class="mid form" action="{{ url("/api/upload") }}" method="post" enctype="multipart/form-data">
            檔案名稱:<input type="file" name="file" id="file" /><br />
            <input type="submit" name="submit" value="上傳檔案" />
        </form>

        <form action="{{ url("/update") }}" method="post">
            {{ csrf_field() }}
            @method('PUT')
            <input type="text" name="item" placeholder="修改內容">
            <input type="hidden" name="no" value="<?php echo "$id"; ?>">
            <input type="submit" value="確定修改">
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