<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Hello extends Controller
{
    public function welcome($id)
    {
        return view('hello', array('id'=>$id));
    }
}
