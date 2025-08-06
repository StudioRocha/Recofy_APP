<?php


namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        return view('index'); // ← resources/views/index.blade.php を表示
    }
}