<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/playlist_information/{mac_address}/{app_type}','ApiController@getPlayListInformation');
Route::get('/get_app_setting/{mac_address}/{app_type?}','ApiController@getAppSetting');
Route::post('/get_epg_data/{offset_minute?}','ApiController@getEpgData');
Route::get('/get_android_version','ApiController@getAndroidVersion');
Route::post('/parseEpgData','ApiController@parseEpgData');
Route::post('/changeLockState','ApiController@changeLockState');

Route::post('/app_purchase','ApiController@saveAppPurchase');
Route::post('/google_pay','ApiController@saveGooglePay');
