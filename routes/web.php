<?php

use Illuminate\Http\File;
use League\Flysystem\MountManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // $adImageDestDirectory = storage_path('app/public/agri_plantpart_color.xlsx');
    // Storage::disk('s3')->put('/dev/master-data/dummy/agri_plantpart_color.xlsx',file_get_contents($adImageDestDirectory));
    // $filename = 'dev/master-data/dummy/agri_plantpart_color.xlsx';
    // echo Storage::cloud()->url($filename);
    return view('welcome');
});
