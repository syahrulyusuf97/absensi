<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('SignController@login');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/createuser', 'CreateUser@store');

//Route::get('/', 'SignController@login');
Route::get('/', 'SignController@login');
Route::match(['get', 'post'], '/login', 'SignController@login')->name('login');
Route::match(['get', 'post'], '/registrasi', 'SignController@registrasi')->name('registrasi');
// Route::match(['get', 'post'], '/reset_password', 'SignController@resetPassword');
// Route::post('/password_reset', 'SignController@passwordReset');
Route::get('/logout', 'SignController@logout');


Route::group(['middleware'=>['auth']], function(){
    // Karyawan
    Route::group(['namespace' => 'karyawan'], function(){
        // Dashboard
        Route::get('/dashboard', 'DashboardController@dashboard');
        // Absen
        Route::get('/absensi', 'AbsensiController@index');
        Route::post('/absen', 'AbsensiController@absen');
        Route::get('/absen/get-data', 'AbsensiController@getAbsen')->name('getKaryawanDataAbsen');
        // Izin
        Route::get('/izin', 'IzinController@index');
        Route::post('/izin/sakit-cuti', 'IzinController@izin')->name('postKaryawanIzin');
        Route::get('/izin/get-data', 'IzinController@getIzin')->name('getKaryawanDataIzin');

        //Profile
        Route::get('/profil', 'DashboardController@profil');
        Route::post('/profil/update-nip', 'DashboardController@updateNIP');
        Route::post('/profil/update-nama', 'DashboardController@updateNama');
        Route::post('/profil/update-email', 'DashboardController@updateEmail');
        Route::post('/profil/update-username', 'DashboardController@updateUsername');
        Route::post('/profil/update-password', 'DashboardController@updatePassword');
        Route::post('/profil/update-ttl', 'DashboardController@updateTtl');
        Route::post('/profil/update-alamat', 'DashboardController@updateAlamat');
        Route::post('/profil/update-foto', 'DashboardController@updateFoto');
    });

    // Manager
    Route::group(['namespace' => 'manager'], function () {
        Route::get('/manager/dashboard', 'DashboardController@dashboard');
        // Absen
        Route::get('/manager/absensi', 'AbsensiController@index');
        Route::get('/manager/absen/get-data', 'AbsensiController@getAbsen')->name('managerGetKaryawanDataAbsen');

        // Izin
        Route::get('/manager/izin', 'IzinController@index');
        Route::get('/manager/izin/get-data', 'IzinController@getIzin')->name('getManagerDataIzin');
        Route::put('/manager/izin/approved/{id}', 'IzinController@approved')->name('approvedManagerDataIzin');
        Route::put('/manager/izin/rejected/{id}', 'IzinController@rejected')->name('rejectedManagerDataIzin');

        // Laporan
        Route::get('/manager/laporan', 'LaporanController@index');
        Route::get('/manager/laporan/pdf', 'LaporanController@pdf');

        //Profile
        Route::get('/manager/profil', 'DashboardController@profil');
        Route::post('/manager/profil/update-nip', 'DashboardController@updateNIP');
        Route::post('/manager/profil/update-nama', 'DashboardController@updateNama');
        Route::post('/manager/profil/update-email', 'DashboardController@updateEmail');
        Route::post('/manager/profil/update-username', 'DashboardController@updateUsername');
        Route::post('/manager/profil/update-password', 'DashboardController@updatePassword');
        Route::post('/manager/profil/update-ttl', 'DashboardController@updateTtl');
        Route::post('/manager/profil/update-alamat', 'DashboardController@updateAlamat');
        Route::post('/manager/profil/update-foto', 'DashboardController@updateFoto');
    });

    // HRD
    Route::group(['namespace' => 'hrd'], function () {
        Route::get('/hrd/dashboard', 'DashboardController@dashboard');
        // Absen
        Route::get('/hrd/absensi', 'AbsensiController@index');
        Route::get('/hrd/absen/get-data', 'AbsensiController@getAbsen')->name('hrdGetKaryawanDataAbsen');
        // Izin
        Route::get('/hrd/izin', 'IzinController@index');
        Route::get('/hrd/izin/get-data', 'IzinController@getIzin')->name('getHRDDataIzin');
        // Laporan
        Route::get('/hrd/laporan', 'LaporanController@index');
        Route::get('/hrd/laporan/pdf', 'LaporanController@pdf');

        //Profile
        Route::get('/hrd/profil', 'DashboardController@profil');
        Route::post('/hrd/profil/update-nip', 'DashboardController@updateNIP');
        Route::post('/hrd/profil/update-nama', 'DashboardController@updateNama');
        Route::post('/hrd/profil/update-email', 'DashboardController@updateEmail');
        Route::post('/hrd/profil/update-username', 'DashboardController@updateUsername');
        Route::post('/hrd/profil/update-password', 'DashboardController@updatePassword');
        Route::post('/hrd/profil/update-ttl', 'DashboardController@updateTtl');
        Route::post('/hrd/profil/update-alamat', 'DashboardController@updateAlamat');
        Route::post('/hrd/profil/update-foto', 'DashboardController@updateFoto');
    });
});
