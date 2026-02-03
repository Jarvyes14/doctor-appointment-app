<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PatientsController extends Controller
{
    public function index()
    {
        return view('admin.patients.index');
    }
}
