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
    <!-- <a href=/LoginPage>登入</a>
    <a href=/RegisterPage>註冊</a> -->
    <!-- <div class="mid"> -->
    <!-- <p>hello world</p> -->

    <form class="form " action="{{ url("/create") }}" method="post">
        {{ csrf_field() }}
        <input type="text" name="item" placeholder="輸入項目">
        <br>
        <input type="submit" value="新增">
        <input type="reset">
        <br>
    </form>
    <br>
    <form class="form mid" action=" " method="get">
        {{ csrf_field() }}
        <table class="mid" width="700" border="1">
            @foreach ($data as $key => $value)
            <tr>
                <td><input type='radio' name='radio' value="{{$value->id}}"></td>
                <td>{{$value->item}}</td>
                <td>{{$value->status}}</td>
                <td>{{$value->update_time}}</td>
                <td>{{$value->update_user}}</td>
            </tr>
            @endforeach
            <input type="submit" value="修改" name="update">
            <input type="submit" value="刪除" name="delete">
            <input type="submit" value="已完成/未完成" name="complete">
        </table>
    </form>

    @if (isset($_GET['delete']))
    <form id="form1" action="{{ url("/delete") }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$_GET['radio']}}">
        @method('DELETE')
    </form>
    <script>
        form1.submit();
    </script>
    @endif

    @if (isset($_GET['complete']))
    <form id="form2" action="{{ url("/complete") }}" method="post">
        {{ csrf_field() }}
        @method('PUT')
        <input type="hidden" name="id" value="{{$_GET['radio']}}">
    </form>
    <!-- </div> -->
    <script>
        form2.submit();
    </script>
    @endif

    @if(session('success'))
    <h1>{{session('success')}}</h1>
    @endif

</body>

</html>
<?php
if (isset($_GET['update']) == true && isset($_GET['radio']) == true) {
    if ($_GET['update'] == '修改') {
        $id = $_GET['radio'];
        return redirect()->to("/update/$id")->send();
    }
}
?>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection