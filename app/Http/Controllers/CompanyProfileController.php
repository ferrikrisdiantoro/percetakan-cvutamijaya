<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    // Menampilkan halaman profil perusahaan
    public function index()
    {
        return view('pelanggan.profile'); // pastikan ada file 'company/profile.blade.php'
    }
}
