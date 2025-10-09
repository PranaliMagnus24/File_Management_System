<?php

namespace App\Http\Controllers\Backend\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileManagementController extends Controller
{
    public function index()
    {
        return view('admin.files.index');
    }
}
