<?php

use Illuminate\Support\Facades\Route;

Route::get('/phpinfo',function (){
    phpinfo();
});

Auth::routes();

Route::get('/test_password/{password}',function ($password){
    return bcrypt($password);
});


Route::get('/',function (){
    return redirect('/news');
});

Route::get('/public/admin', function () {
    return redirect('/admin/login');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/news','HomeController@news');
Route::get('/faq','HomeController@faq');
Route::get('/instruction/{tag_name?}','HomeController@instruction');

Route::get('/device/login','HomeController@deviceLogin');
Route::post('/device/login','HomeController@postDeviceLogin');
Route::any('/device/payment/cancel','HomeController@PaymentCancel');

Route::get('/become-a-reseller','HomeController@showResellerPage');

Route::Group(['middleware'=>'check-device-auth','prefix'=>'device'],function(){
    Route::get('/playlists','HomeController@showPlaylists');
    Route::post('/savePlaylist','HomeController@savePlaylist');
    Route::get('getPlaylistUrlDetail/{playlist_url_id}','HomeController@getPlaylistUrlDetail');
    Route::get('checkPlaylistPinCode/{playlist_url_id}/{pin_code}','HomeController@checkPlaylistPinCode');
    Route::delete('deletePlayListUrl','HomeController@deletePlayListUrl');
    Route::get('/activation','HomeController@Activation');
    Route::get('/activation-test','HomeController@ActivationTest');
    Route::post('/saveActivation','HomeController@saveActivation');
    Route::get('/logout','HomeController@deviceLogout');
});

Route::get('/mylist','HomeController@myList');
Route::post('/mylist/saveMacAdress','HomeController@saveMacAdress');
Route::post('/mylist/delete','HomeController@deleteMyList');

Route::get('/terms&conditions','HomeController@terms');
Route::get('/privacy&policy','HomeController@privacy');
Route::get('/sitemap.xml',function (){
    $xmlString=file_get_contents(public_path("sitemap.xml"));
    return response()->view('frontend.sitemap',compact('xmlString') )->header('Content-Type', 'text/xml');
});

Route::get('/activation','HomeController@Activation');
Route::post('/activation/saveActivation','HomeController@saveActivation');
Route::post('/checkMacValid','HomeController@checkMacValid');
Route::post('/paypal/order/create','HomeController@createOrder');
Route::post('/paypal/order/capture','HomeController@captureOrder');

Route::Group(['prefix'=>'admin','namespace'=>'Admin'], function (){
    Route::get('/login','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('admin.login');
    Route::get('/register','Auth\RegisterController@showRegistrationForm');
    Route::post('/register','Auth\RegisterController@register')->name('admin.register');
    Route::Group(['middleware'=>'auth:admin'],function (){
        Route::post('/logout','Auth\LoginController@logOut')->name('admin.logout');
        Route::get('/logout','Auth\LoginController@logOut');

        Route::Group(['middleware'=>'is_admin'],function (){
            Route::get('/news','NewsController@index');
            Route::get('/news/create/{id?}','NewsController@createNewsSection');
            Route::post('/news/save','NewsController@saveNewsSection');
            Route::post('/news/delete/{id}','NewsController@deleteNewsSection');

            Route::get('/faq','FaqController@index');
            Route::post('/faq/save','FaqController@save');

            Route::get('/reseller-content','AdminController@showResellerContent');
            Route::post('/saveResellerContent','AdminController@saveResellerContent');

            Route::get('instruction/tags','InstructionController@Tags');
            Route::post('instruction/createTag','InstructionController@createTag');
            Route::post('instruction/deleteTag/{id}','InstructionController@deleteTag');
            Route::get('instruction/page/{tag_id}','InstructionController@instructionTag');
            Route::post('instruction/page/save/{tag_id}','InstructionController@saveInstructionPage');
            
            Route::get('dns','AllowdnsController@index');
            Route::post('dns/createDNS','AllowdnsController@addDns');
            Route::post('dns/deleteDNS/{id}','AllowdnsController@deleteDns');
            // Route::get('dns/page/{dns_id}','AllowdnsController@allowDns');
            // Route::post('dns/page/save/{dns_id}','AllowdnsController@saveDnsPage');

            Route::get('mylist','AdminController@showMyListPageContent');
            Route::post('mylist/save','AdminController@saveMyListContent');

            Route::get('activation','AdminController@showActivationPageContent');
            Route::post('activation/save','AdminController@saveActivationContent');

            Route::get('playlist_package','PlayListPriceController@index');
            Route::get('playlist_package/create/{id?}','PlayListPriceController@createPackage');
            Route::post('playlist_package/delete/{id}','PlayListPriceController@deletePackage');
            Route::post('playlist_package/save','PlayListPriceController@savePackage');

            Route::get('terms','AdminController@showTermsContent');
            Route::post('terms/save','AdminController@saveTermsContent');

            Route::get('privacy','AdminController@showPrivacyContent');
            Route::post('privacy/save','AdminController@savePrivacyContent');

            Route::get('transactions','AdminController@transactions');

            Route::get('seo_setting','AdminController@showSeoSetting');
            Route::post('saveSeoSetting','AdminController@saveSeoSetting');

            Route::get('showDemoUrl','AdminController@showDemoUrl');
            Route::post('saveDemoUrl','AdminController@saveDemoUrl');

            Route::get('trial-setting','AdminController@showTrialSetting');
            Route::post('saveTrialSetting','AdminController@saveTrialSetting');

            Route::get('showAppBackground','AdminController@showAppBackground');
            Route::post('saveThemes','AdminController@saveThemes');

            Route::get('showAdverts','AdminController@showAdverts');
            Route::post('saveAdverts','AdminController@saveAdverts');

            Route::get('showStripeSetting','AdminController@showStripeSetting');
            Route::post('saveStripeSetting','AdminController@saveStripeSetting');

            Route::get('showPaypalSetting','AdminController@showPaypalSetting');
            Route::post('savePaypalSetting','AdminController@savePaypalSetting');

            Route::get('showCryptoApiKey','AdminController@showCryptoApiKey');
            Route::post('saveCryptoApiKey','AdminController@saveCryptoApiKey');

            Route::get('showCoinList','AdminController@showCoinList');
            Route::post('saveCoinList','AdminController@saveCoinList');

            Route::get('android-update','AdminController@showAndroidUpdate');
            Route::post('saveAndroidUpdate','AdminController@saveAndroidUpdate');

            Route::get('notification','AdminController@showNotification');
            Route::post('saveNotification','AdminController@saveNotification');

            Route::get('language','AdminController@showLanguage');
            Route::post('language/create','AdminController@createLanguage');
            Route::post('language/delete/{id}','AdminController@deleteLanguage');

            Route::get('word','AdminController@showWord');
            Route::post('word/create','AdminController@createWord');
            Route::post('word/delete/{id}','AdminController@deleteWord');

            Route::get('language-word/{language_id}','AdminController@showLanguageWord');
            Route::post('saveLanguageWord/{language_id}','AdminController@saveLanguageWord');
            Route::post('saveLanguageFile/{language_id}','AdminController@saveLanguageFile');

            Route::get('showPaymentVisibility','AdminController@showPaymentVisibility');
            Route::post('savePaymentVisibility','AdminController@savePaymentVisibility');

            Route::get('resellers','AdminController@showResellers');
            Route::post('reseller/create','AdminController@createReseller');
            Route::post('reseller/delete','AdminController@deleteReseller');

            Route::get('reseller_packages','AdminController@showResellerPackages');
            Route::post('price_package/create','AdminController@createResellerPackages');
            Route::post('price_package/delete','AdminController@deletePricePackage');

            Route::get('playlist/{id}','PlayListController@showDetail');


        });
        Route::get('/','AdminController@index');
        Route::get('playlists','PlayListController@index');
        Route::post('playlist/getPlaylists','PlayListController@getPlaylists');
        Route::post('playlist/activate','PlayListController@activate');


        Route::get('profile','AdminController@showProfile');
        Route::post('updateProfile','AdminController@updateProfile');
    });
});
