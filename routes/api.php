<?php

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

// Route::get('notification', function() {
//     $order = App\Models\Order\Order::latest()->first();
//     App\Events\NewOrder::dispatch($order);
// });

Route::get('seo/{slug}','SeoController@index');
Route::group(['prefix' => 'v1'], function() {

    Route::group(['prefix' => 'front-end'], function() {
        Route::get('products', 'Api\ProductController@index');
        Route::get('bundles', 'Api\BundleController@index');
        Route::resource('schools', 'Api\SchoolController');
        Route::get('all-schools', 'Api\SchoolController@listall');

        Route::get('products/{category?}', 'Api\ProductController@index');
        Route::get('categories', 'Admin\CategoryController@index');
        Route::resource('payment', 'Api\PaymentController');
        Route::resource('order', 'Api\OrderController');
        Route::get('promotion-code/{code}/validate', 'Admin\PromotionCodeController@validatePromotionCode');
        Route::get('terms','Admin\TermsAndConditionController@index');
    });




    Route::post('contact-us','Api\ContactUsController@storeSchoolList');
    Route::post('help/contact-us','Api\ContactUsController@store');
    Route::get('cities','Api\SupplyController@cities');
    Route::get('city/schools','Api\SupplyController@schoolsByCity');
    Route::get('school/{school}/levels','Api\SupplyController@schoolLevels');


    /*************** Product Routes ***************/

    Route::group(['prefix'=>'product'], function (){
        Route::get('quantity/check/{product_id}/{quantity}','Api\ProductController@checkQuantity');
        Route::get('search/{query_param}', 'Api\ProductController@search');

    });
        /*************** OAUTH Routes *****************/

        Route::group(['prefix' => 'oauth','middleware' => 'must-confirmed-email'], function(){
            Route::post('signin', 'AuthController@signin');
            Route::post('register', 'AuthController@register');
            Route::get('registered/confirm', 'AuthController@registerConfirmation')->name('registeredConfirmation');
            Route::post('forgot-password', 'AuthController@sendPasswordResetEmail');
            Route::post('reset', 'AuthController@reset');
            Route::post('admin-login', 'AuthController@adminLogin');
        });

        /*************** API Middleware ***************/
        Route::group(['prefix'=>'order'],function (){
           Route::post('success/{reference}','Api\OrderController@successPost')->name('success-post');
           Route::get('success/{reference}','Api\OrderController@successGet')->name('success-get');

            Route::post('failure/{reference}','Api\OrderController@failurePost')->name('failure-post');
            Route::get('failure/{reference}','Api\OrderController@failureGet')->name('failure-get');
        });

        Route::group(['middleware' => 'auth:api','namespace' => 'Api'], function() {

            /*************** User Routes ***************/
            Route::group(['prefix' => 'user'], function() {
                Route::put('/', 'UserController@update');
                Route::post('change-password','UserController@changePassword');
                Route::get('profile', 'UserController@userProfile');
                Route::get('children', 'UserController@getChildren');
                Route::get('orders', 'UserController@getOrders');
                Route::get('orders/{id}', 'UserController@showOrder');
                Route::get('search/{keyword}','UserController@searchUser');
                Route::get('unused-promo', 'UserController@unUsedPromoCodes');
                Route::post('save-promo', 'UserController@savePromoCodes');
            });

            /*************** Child Routes ****************/
            Route::group(['prefix' => 'child'], function() {
                Route::post('','ChildController@store');
                Route::delete('{child}','ChildController@remove');

            });

            Route::group(['prefix' => 'invoice'], function() {
                Route::post('','InvoiceController@index');

            });
        });

        Route::group(['namespace' => 'Api'], function() {
//            Route::resource('bundles', 'BundleController');
            Route::get('bundles/search/{keyword}','BundleController@searchBundle');

            /*************** Cart Routes ****************/
            Route::group(['prefix' => 'cart'], function() {
                Route::get('', 'CartController@show');
                Route::post('', 'CartController@storeOrUpdate');
                Route::delete('', 'CartController@destroy');
                Route::post('supply-list', 'CartController@supplyListAddToCart');
                Route::get('validate', 'CartController@validateCart');
            });

            /*************** Api Checkout Routes ****************/

            Route::group(['prefix' => 'checkout', 'middleware'=>'auth:api'], function() {
                Route::post('', 'CheckoutController@index');

            });

            Route::post('check-promo','CheckoutController@checkPromoCode');
            Route::post('remove-promo','CheckoutController@removePromo')->middleware('auth:api');
            Route::post('check-promo/logged-in','CheckoutController@checkPromoCodeLogged')->middleware('auth:api');

            /*************** Api Supply  Routes ****************/

            Route::group(['prefix' => 'supply'], function() {
                Route::post('','SupplyController@index');
            });

            /*************** Api Product  Routes ****************/

            Route::group(['prefix' => 'product'], function() {
                Route::get('types', 'ProductController@types');
                Route::get('sizes', 'ProductController@sizes');
                Route::get('colors', 'ProductController@colors');
                Route::get('{id}','ProductController@show');
                Route::post('quantity', 'ProductController@productQuantity');
            });

        });

    /*************** Admin Routes ****************/
    Route::group(['prefix' => 'admin','namespace' => 'Admin'], function() {
    	Route::get('supplylist/view/{id}', 'SupplyListController@view');
    	Route::post('supplylist/{supplylist}','SupplyListController@update');
        Route::get('users/list', 'UserController@allUsers');
//        Route::post('roles','UserController@updateUserRole');
        Route::apiResources([
            'promotion-code'    =>  'PromotionCodeController',
            'parents'           =>  'UserController',
            'cart'              =>  'CartController',
            'invoice'           =>  'InvoiceController',
            'order'             =>  'OrderController',
            'product'           =>  'ProductController',
            'shipment'          =>  'ShipmentController',
            'payment'           =>  'PaymentController',
            'category'          =>  'CategoryController',
            'schools'           =>  'SchoolController',
            'supplylist'        =>  'SupplyListController',
            'terms'             =>  'TermsAndConditionController',
            'levels'            =>  'LevelController',
            'roles'             =>  'RolesController'
        ]);

        Route::group(['prefix'=>'user'], function() {
            Route::get('{id}','UserController@show');
            Route::get('search/{keyword}','UserController@searchUser');

        });
        Route::group(['prefix'=>'schools'], function() {
            Route::get('search/{query}','SchoolController@searchSchool');
        });


        Route::group(['prefix'=>'orders'], function() {
            Route::get('pending','OrderController@pending');
            Route::get('confirmed','OrderController@confirmed');
            Route::get('cancelled','OrderController@cancelled');
            Route::get('completed','OrderController@completed');
        });

        Route::group(['prefix'=>'inbox'], function() {
            Route::get('/','EmailController@index');
            Route::get('unread','EmailController@unreadCount');
            Route::put('{id}','EmailController@changeStatus');
        });

    });
    
    /********** LogisticManager Routes ************/
    Route::group(['prefix' => 'logistic-manager','namespace' => 'LogisticManager','middleware'=>'auth:api'], function() {

        Route::resource('products', 'ProductController');
        Route::resource('product_types', 'ProductTypesController');
        Route::post('products/import','ProductController@importCsv');
        Route::post('pictures/import','ProductController@importImages');
        Route::resource('bundles', 'BundleController');
        Route::get('bundles/search/{keyword}','BundleController@searchBundle');


            Route::patch('order/{order}/change-status/{statusId}', 'OrderController@orderStatusChange');
        Route::resource('order', 'OrderController');

        Route::group(['prefix'=>'orders'], function() {
            Route::get('pending','OrderController@pending');
            Route::get('confirmed','OrderController@confirmed');
            Route::get('completed','OrderController@completed');
            Route::get('cancelled','OrderController@cancelled');
            Route::put('update/billing/{id}','OrderController@updateBillingAddress');
            Route::put('update/delivery/{id}','OrderController@updateDeliveryAddress');

        });
        Route::get('products/search/{keyword}','ProductController@searchProduct');

        Route::put('order-address/{address}', 'OrderController@orderAddress');
    });
    Route::post('upload-image','LogisticManager\ProductController@fileUpload');

    Route::get('order/invoice/{reference}','Api\InvoiceController@showInvoice')->name('orderInvoice');
    Route::get('overview', 'LogisticManager\OrderController@orderWidgets');
    Route::get('overview/revenue-status', 'LogisticManager\RevenuesController@orderGraph');
    Route::post('supply-list/product', 'Admin\SupplyListController@replaceProductInSupplyList');
    Route::get('admin/supplylist/search/{keyword}', 'Admin\SupplyListController@searchSupplyList');
});