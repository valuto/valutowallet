<?php

namespace Controllers;

class HomeController extends Controller
{
    public function index()
    {
        include __DIR__ . "/../../view/header.php";
        include __DIR__ . "/../../view/home.php";
        include __DIR__ . "/../../view/footer.php";
    }

    public function redirect()
    {
        return redirect('');
    }
}