<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // សម្រាប់ពេលនេះ យើងគ្រាន់តែឱ្យវាបង្ហាញទំព័រ Dashboard ស្រួលមើលសិន
        return view('admin.dashboard');
    }
}