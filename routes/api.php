<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::fallback(function(){
    return response()->json([
        'success'    => false,
        'message' => 'Not Found'], 404);
});

// Api version 1
Route::group(['prefix' => 'v1'], function(){
    // Prefix for auth
    Route::group(['prefix' => 'auth'], function () {
        Route::group(['namespace' => 'Api'], function(){
            Route::post('login', 'SignController@login');
            Route::post('signup', 'SignController@signup');
          
            Route::group(['middleware' => 'auth:api'], function() {
                Route::get('logout', 'SignController@logout');
            });
        });
    });

    Route::group(['prefix' => 'karyawan'], function(){
        // Karyawan
        Route::group(['namespace' => 'Api\Karyawan'], function(){      
            Route::group(['middleware' => 'auth:api'], function() {
                // Dashboard
                Route::get('/dashboard', 'DashboardController@dashboard');

                // Absen
                Route::post('/absen', 'AbsensiController@absen');
                Route::get('/absen/get-data', 'AbsensiController@getAbsen');

                // Izin
                Route::post('/izin/sakit-cuti', 'IzinController@izin');
                Route::get('/izin/get-data', 'IzinController@getIzin');

                //Profile
                Route::post('/profil/update-nip', 'DashboardController@updateNIP');
                Route::post('/profil/update-nama', 'DashboardController@updateNama');
                Route::post('/profil/update-email', 'DashboardController@updateEmail');
                Route::post('/profil/update-username', 'DashboardController@updateUsername');
                Route::post('/profil/update-password', 'DashboardController@updatePassword');
                Route::post('/profil/update-ttl', 'DashboardController@updateTtl');
                Route::post('/profil/update-alamat', 'DashboardController@updateAlamat');
            });
        });
    });

    Route::group(['prefix' => 'manager'], function(){
        // Manager
        Route::group(['namespace' => 'Api\Manager'], function(){      
            Route::group(['middleware' => 'auth:api'], function() {
                // Dashboard
                Route::get('/dashboard', 'DashboardController@dashboard');

                // Absen
                Route::get('/absen/get-data', 'AbsensiController@getAbsen');

                // Izin
                Route::get('/izin/get-data', 'IzinController@getIzin');
                Route::put('/izin/approved/{id}', 'IzinController@approved');
                Route::put('/izin/rejected/{id}', 'IzinController@rejected');

                // Laporan
                Route::get('/get-karyawan', 'LaporanController@getKaryawan');
                Route::get('/laporan', 'LaporanController@laporan');

                //Profile
                Route::post('/profil/update-nip', 'DashboardController@updateNIP');
                Route::post('/profil/update-nama', 'DashboardController@updateNama');
                Route::post('/profil/update-email', 'DashboardController@updateEmail');
                Route::post('/profil/update-username', 'DashboardController@updateUsername');
                Route::post('/profil/update-password', 'DashboardController@updatePassword');
                Route::post('/profil/update-ttl', 'DashboardController@updateTtl');
                Route::post('/profil/update-alamat', 'DashboardController@updateAlamat');
            });
        });
    });

    Route::group(['prefix' => 'hrd'], function(){
        // HRD
        Route::group(['namespace' => 'Api\HRD'], function(){      
            Route::group(['middleware' => 'auth:api'], function() {
                // Dashboard
                Route::get('/dashboard', 'DashboardController@dashboard');

                // Absen
                Route::get('/absen/get-data', 'AbsensiController@getAbsen');

                // Izin
                Route::get('/izin/get-data', 'IzinController@getIzin');

                // Laporan
                Route::get('/get-karyawan', 'LaporanController@getKaryawan');
                Route::get('/laporan', 'LaporanController@laporan');

                //Profile
                Route::post('/profil/update-nip', 'DashboardController@updateNIP');
                Route::post('/profil/update-nama', 'DashboardController@updateNama');
                Route::post('/profil/update-email', 'DashboardController@updateEmail');
                Route::post('/profil/update-username', 'DashboardController@updateUsername');
                Route::post('/profil/update-password', 'DashboardController@updatePassword');
                Route::post('/profil/update-ttl', 'DashboardController@updateTtl');
                Route::post('/profil/update-alamat', 'DashboardController@updateAlamat');
            });
        });
    });
});
