<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Welcome\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Expert\ExpertController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MarketPricesController;
use App\Http\Controllers\Admin\EnquiriesController;

Route::get('/',[WelcomeController::class,'index'])->name('index');
Route::get('/image-view',[WelcomeController::class,'imageView'])->name('imageView');
Route::get('/image/{template?}/{image?}',[WelcomeController::class,'imageView2'])->name('imageView2');
Route::get('/sitemap.xml',[WelcomeController::class,'siteMapXml'])->name('siteMapXml');
Route::get('/search',[WelcomeController::class,'search'])->name('search');
Route::get('/market/live',[WelcomeController::class,'marketLive'])->name('marketLive');
Route::post('/contact-mail',[WelcomeController::class,'contactMail'])->name('contactMail');
Route::post('/subscribe',[WelcomeController::class,'subscribe'])->name('subscribe');
Route::get('/switch/{lang?}',[WelcomeController::class,'language'])->name('language');
Route::get('/geo/filter/{id}',[WelcomeController::class,'geo_filter'])->name('geo_filter');

Route::get('/ads/category/{slug}',[WelcomeController::class,'serviceCategory'])->name('serviceCategory');
Route::get('/ads/{slug}',[WelcomeController::class,'serviceView'])->name('serviceView');


Route::get('/blog/category/{slug}',[WelcomeController::class,'blogCategory'])->name('blogCategory');
Route::get('/blog/author/{id}/{slug}',[WelcomeController::class,'blogAuthor'])->name('blogAuthor');
Route::get('/blog/tag/{slug}',[WelcomeController::class,'blogTag'])->name('blogTag');
Route::get('/blog/archives/{slug}',[WelcomeController::class,'blogArchives'])->name('blogArchives');
Route::get('/blog/search',[WelcomeController::class,'blogSearch'])->name('blogSearch');
Route::get('/blog/{slug}',[WelcomeController::class,'blogView'])->name('blogView');
Route::post('/blog-comments/{slug}',[WelcomeController::class,'blogComments'])->name('blogComments');

//Auth Route Start

Route::get('/login',[AuthController::class,'login'])->name('login');
Route::post('/login',[AuthController::class,'loginStore']);
Route::get('/forgot-password',[AuthController::class,'forgotPassword'])->name('forgotPassword');
Route::post('/forgot-password',[AuthController::class,'forgotPasswordStore']);
Route::get('/reset-password',[AuthController::class,'resetPassword'])->name('resetPassword');
Route::post('/reset-password',[AuthController::class,'resetPasswordCheck'])->name('resetPasswordCheck');
Route::post('/reset-password/resend',[AuthController::class,'resetPasswordResend'])->name('resetPasswordResend');
Route::get('/reset-password/new-password',[AuthController::class,'newPassword'])->name('newPassword');
Route::post('/reset-password/new-password',[AuthController::class,'newPasswordStore']);
Route::get('/register',[AuthController::class,'register'])->name('register');
Route::post('/register',[AuthController::class,'registerStore']);
Route::post('/log-out',[AuthController::class,'logout'])->name('logout');
Route::get('/auth/{provider}',[AuthController::class,'redirectToProvider'])->whereIn('provider',['google','facebook'])->name('socialRedirect');
Route::get('/auth/{provider}/callback',[AuthController::class,'handleProviderCallback'])->whereIn('provider',['google','facebook'])->name('socialCallback');
Route::get('/register-verify',[AuthController::class,'registerVerify'])->name('registerVerify');
Route::post('/register-verify',[AuthController::class,'registerVerifyStore']);
Route::post('/register-verify/resend',[AuthController::class,'registerVerifyResend'])->name('registerVerifyResend');
Route::post('/login-otp/send',[AuthController::class,'loginOtpSend'])->name('loginOtpSend');
Route::post('/login-otp/verify',[AuthController::class,'loginOtpVerify'])->name('loginOtpVerify');

Route::get('/{slug}',[WelcomeController::class,'pageView'])->name('pageView');

// Buyer Route Group Start

Route::group(['prefix'=>'buyer', 'as'=>'buyer.','middleware'=>['auth','role:buyer']], function(){

    Route::get('/dashboard',[BuyerController::class,'dashboard'])->name('dashboard');
    Route::get('/profile-overview',[BuyerController::class,'profileOverview'])->name('profileOverview');
    Route::any('/preferences',[BuyerController::class,'preferences'])->name('preferences');
    Route::get('/saved-searches',[BuyerController::class,'savedSearches'])->name('savedSearches');
    Route::get('/saved-searches/run/{id}',[BuyerController::class,'savedSearchRun'])->name('savedSearchRun');
    Route::post('/saved-searches/{action}/{id?}',[BuyerController::class,'savedSearchAction'])->name('savedSearchAction');
    Route::any('/payment-methods',[BuyerController::class,'paymentMethods'])->name('paymentMethods');
    Route::post('/payment-methods/{action}/{id}',[BuyerController::class,'paymentMethodAction'])->name('paymentMethodAction');
    Route::get('/addresses',[BuyerController::class,'addresses'])->name('addresses');
    Route::post('/addresses/{action}/{id?}',[BuyerController::class,'addressAction'])->name('addressAction');
    Route::any('/business-info',[BuyerController::class,'businessInfo'])->name('businessInfo');
    Route::any('/business-info/documents/{action}/{id?}',[BuyerController::class,'businessDocumentAction'])->name('businessDocumentAction');
    Route::get('/orders',[BuyerController::class,'orders'])->name('orders');
    Route::get('/community',[BuyerController::class,'community'])->name('community');
    Route::any('/community/{action}/{id?}',[BuyerController::class,'communityAction'])->name('communityAction');
    Route::get('/messages',[BuyerController::class,'messages'])->name('messages');
    Route::post('/messages',[BuyerController::class,'messageSend'])->name('messageSend');
    Route::get('/messages/poll',[BuyerController::class,'messagePoll'])->name('messagesPoll');
    Route::get('/marketplace',[BuyerController::class,'marketplace'])->name('marketplace');
    Route::get('/marketplace/{id}',[BuyerController::class,'marketplaceView'])->whereNumber('id')->name('marketplaceView');
    Route::get('/wishlist',[BuyerController::class,'wishlist'])->name('wishlist');
    Route::post('/wishlist/{id}',[BuyerController::class,'wishlistToggle'])->name('wishlistToggle');
    Route::get('/ads',[BuyerController::class,'ads'])->name('ads');
    Route::any('/ads/{action}/{id}',[BuyerController::class,'adsAction'])->name('adsAction');
    Route::get('/enquiries',[BuyerController::class,'enquiries'])->name('enquiries');
    Route::any('/enquiries/{action}/{id?}',[BuyerController::class,'enquiriesAction'])->name('enquiriesAction');
    Route::post('/profile/photo',[BuyerController::class,'profilePhoto'])->name('profilePhoto');
    Route::any('/profile',[BuyerController::class,'profile'])->name('profile');
    Route::any('/change-password',[BuyerController::class,'changePassword'])->name('changePassword');
    Route::get('/settings',[BuyerController::class,'settings'])->name('settings');
    Route::post('/settings/toggle',[BuyerController::class,'settingsToggle'])->name('settingsToggle');
    Route::post('/settings/preferences',[BuyerController::class,'settingsPreferences'])->name('settingsPreferences');
    Route::post('/settings/deactivate',[BuyerController::class,'deactivate'])->name('deactivate');
    Route::post('/become-seller',[BuyerController::class,'becomeSeller'])->name('becomeSeller');

});

// Seller Route Group Start

Route::group(['prefix'=>'seller', 'as'=>'seller.','middleware'=>['auth','role:seller']], function(){

    Route::get('/dashboard',[SellerController::class,'dashboard'])->name('dashboard');
    Route::get('/profile-overview',[SellerController::class,'profileOverview'])->name('profileOverview');
    Route::get('/orders',[SellerController::class,'orders'])->name('orders');
    Route::get('/enquiries',[SellerController::class,'enquiries'])->name('enquiries');
    Route::any('/enquiries/{action}/{id}',[SellerController::class,'enquiriesAction'])->name('enquiriesAction');
    Route::get('/marketplace',[SellerController::class,'marketplace'])->name('marketplace');
    Route::get('/marketplace/{id}',[SellerController::class,'marketplaceView'])->whereNumber('id')->name('marketplaceView');
    Route::get('/wishlist',[SellerController::class,'wishlist'])->name('wishlist');
    Route::post('/wishlist/{id}',[SellerController::class,'wishlistToggle'])->name('wishlistToggle');
    Route::get('/saved-searches',[SellerController::class,'savedSearches'])->name('savedSearches');
    Route::get('/saved-searches/run/{id}',[SellerController::class,'savedSearchRun'])->name('savedSearchRun');
    Route::post('/saved-searches/{action}/{id?}',[SellerController::class,'savedSearchAction'])->name('savedSearchAction');
    Route::get('/addresses',[SellerController::class,'addresses'])->name('addresses');
    Route::post('/addresses/{action}/{id?}',[SellerController::class,'addressAction'])->name('addressAction');
    Route::any('/payment-methods',[SellerController::class,'paymentMethods'])->name('paymentMethods');
    Route::post('/payment-methods/{action}/{id}',[SellerController::class,'paymentMethodAction'])->name('paymentMethodAction');
    Route::any('/business-info',[SellerController::class,'businessInfo'])->name('businessInfo');
    Route::any('/business-info/documents/{action}/{id?}',[SellerController::class,'businessDocumentAction'])->name('businessDocumentAction');
    Route::get('/settings',[SellerController::class,'settings'])->name('settings');
    Route::post('/settings/toggle',[SellerController::class,'settingsToggle'])->name('settingsToggle');
    Route::post('/settings/preferences',[SellerController::class,'settingsPreferences'])->name('settingsPreferences');
    Route::post('/settings/deactivate',[SellerController::class,'deactivate'])->name('deactivate');
    Route::post('/profile/photo',[SellerController::class,'profilePhoto'])->name('profilePhoto');
    Route::any('/profile',[SellerController::class,'profile'])->name('profile');
    Route::any('/change-password',[SellerController::class,'changePassword'])->name('changePassword');
    Route::get('/listings',[SellerController::class,'listings'])->name('listings');
    Route::any('/listings/{action}/{id?}',[SellerController::class,'listingsAction'])->name('listingsAction');
    Route::get('/payments',[SellerController::class,'payments'])->name('payments');
    Route::get('/notifications',[SellerController::class,'notifications'])->name('notifications');
    Route::post('/notifications/clear',[SellerController::class,'notificationsClear'])->name('notificationsClear');
    Route::post('/notifications/{id}/delete',[SellerController::class,'notificationDelete'])->name('notificationDelete');
    Route::get('/community',[SellerController::class,'community'])->name('community');
    Route::any('/community/{action}/{id?}',[SellerController::class,'communityAction'])->name('communityAction');
    Route::get('/messages',[SellerController::class,'messages'])->name('messages');
    Route::post('/messages',[SellerController::class,'messageSend'])->name('messageSend');
    Route::get('/messages/poll',[SellerController::class,'messagePoll'])->name('messagesPoll');

});

// Expert Route Group Start

Route::group(['prefix'=>'expert', 'as'=>'expert.','middleware'=>['auth','role:expert']], function(){

    Route::get('/dashboard',[ExpertController::class,'dashboard'])->name('dashboard');
    Route::get('/qa',[ExpertController::class,'qa'])->name('qa');
    Route::get('/earnings',[ExpertController::class,'earnings'])->name('earnings');
    Route::post('/earnings/withdraw',[ExpertController::class,'withdraw'])->name('withdraw');
    Route::post('/earnings/withdrawals/{id}/cancel',[ExpertController::class,'withdrawCancel'])->name('withdrawCancel');
    Route::any('/payment-methods',[ExpertController::class,'paymentMethods'])->name('paymentMethods');
    Route::post('/payment-methods/{action}/{id}',[ExpertController::class,'paymentMethodAction'])->name('paymentMethodAction');
    Route::get('/availability',[ExpertController::class,'availability'])->name('availability');
    Route::post('/availability/slots',[ExpertController::class,'availabilitySlotAdd'])->name('availabilitySlotAdd');
    Route::post('/availability/slots/{id}/delete',[ExpertController::class,'availabilitySlotDelete'])->name('availabilitySlotDelete');
    Route::post('/availability/settings',[ExpertController::class,'availabilitySettings'])->name('availabilitySettings');
    Route::get('/articles',[ExpertController::class,'articles'])->name('articles');
    Route::any('/articles/{action}/{id?}',[ExpertController::class,'articleAction'])->whereIn('action',['create','edit','delete'])->name('articleAction');
    Route::post('/questions/{id}/answer',[ExpertController::class,'questionAnswer'])->name('questionAnswer');
    Route::post('/answers/{id}',[ExpertController::class,'answerUpdate'])->name('answerUpdate');
    Route::get('/consultations',[ExpertController::class,'consultations'])->name('consultations');
    Route::post('/consultations/{id}/reschedule',[ExpertController::class,'consultationReschedule'])->name('consultationReschedule');
    Route::post('/consultations/{id}/{action}',[ExpertController::class,'consultationAction'])->whereIn('action',['accept','decline','cancel','complete'])->name('consultationAction');
    Route::post('/profile/details',[ExpertController::class,'profileDetails'])->name('profileDetails');
    Route::post('/profile/photo',[ExpertController::class,'profilePhoto'])->name('profilePhoto');
    Route::any('/profile',[ExpertController::class,'profile'])->name('profile');
    Route::any('/change-password',[ExpertController::class,'changePassword'])->name('changePassword');
    Route::get('/settings',[ExpertController::class,'settings'])->name('settings');
    Route::post('/settings/toggle',[ExpertController::class,'settingsToggle'])->name('settingsToggle');
    Route::post('/settings/preferences',[ExpertController::class,'settingsPreferences'])->name('settingsPreferences');
    Route::post('/settings/deactivate',[ExpertController::class,'deactivate'])->name('deactivate');
    Route::get('/community',[ExpertController::class,'community'])->name('community');
    Route::any('/community/{action}/{id?}',[ExpertController::class,'communityAction'])->name('communityAction');
    Route::get('/messages',[ExpertController::class,'messages'])->name('messages');
    Route::post('/messages',[ExpertController::class,'messageSend'])->name('messageSend');
    Route::get('/messages/poll',[ExpertController::class,'messagePoll'])->name('messagesPoll');

    // Pages that wait for their design (consultations, q&a, articles, availability, earnings, reviews).
    foreach(array_keys(ExpertController::SOON) as $page){
        Route::get('/'.$page,[ExpertController::class,'soon'])->defaults('page',$page)->name($page);
    }

});

// Old single customer dashboard URLs, they redirect to the buyer/seller dashboard.
Route::group(['prefix'=>'customer', 'as'=>'customer.','middleware'=>['auth']], function(){

    Route::get('/dashboard',[DashboardController::class,'home'])->name('dashboard');
    Route::any('/profile',[DashboardController::class,'profile'])->name('profile');
    Route::any('/change-password',[DashboardController::class,'changePassword'])->name('changePassword');
    Route::get('/listings',[DashboardController::class,'listings'])->name('listings');
    Route::any('/listings/{action}/{id?}',[DashboardController::class,'listingsAction'])->name('listingsAction');
    Route::get('/payments',[DashboardController::class,'payments'])->name('payments');

});


// Admin Route Group Start

Route::group(['prefix'=>'admin', 'as'=>'admin.','middleware'=>['auth','role:admin','permission']], function(){

Route::get('/dashboard',[AdminController::class,'dashboard'])->name('dashboard');
Route::any('/my-profile',[AdminController::class,'myProfile'])->name('myProfile');

Route::get('/editor/{type}/{id}',[AdminController::class,'contentEditor'])->name('contentEditor');
Route::any('/editor-action/{action}/{id?}',[AdminController::class,'contentEditorAction'])->name('contentEditorAction');

// Medies Library Route
Route::get('/medies',[AdminController::class,'medies'])->name('medies');
Route::post('/medies-file-upload',[AdminController::class,'mediesFileUpload'])->name('mediesFileUpload');
Route::post('/medies/create',[AdminController::class,'mediesCreate'])->name('mediesCreate');
Route::match(['get','post'],'/medies/edit/{id}',[AdminController::class,'mediesEdit'])->name('mediesEdit');
Route::get('/medies/delete/{id}',[AdminController::class,'mediesDelete'])->name('mediesDelete');
// Medies Library Route End


//Page Management
Route::get('/pages',[AdminController::class,'pages'])->name('pages');
Route::any('/pages/{action}/{id?}',[AdminController::class,'pagesAction'])->name('pagesAction');
//Page Management End

// Posts Comments Route End
Route::get('/posts/comments/all',[PostsController::class,'postsCommentsAll'])->name('postsCommentsAll');
Route::get('/posts/comments/post/{id}',[PostsController::class,'postsComments'])->name('postsComments');
Route::any('/posts/comments/{action}/{id}',[PostsController::class,'postsCommentsAction'])->name('postsCommentsAction');
// Posts Comments Route End

// Posts Categories Route
Route::get('/posts/categories',[PostsController::class,'postsCategories'])->name('postsCategories');
Route::any('/posts/categories/{action}/{id?}',[PostsController::class,'postsCategoriesAction'])->name('postsCategoriesAction');
// Posts Categories Route End

// Posts Tags Route
Route::get('/posts/tags',[PostsController::class,'postsTags'])->name('postsTags');
Route::any('/posts/tags/{action}/{id?}',[PostsController::class,'postsTagsAction'])->name('postsTagsAction');
// Posts Tags Route End

// Posts Route
Route::get('/posts',[PostsController::class,'posts'])->name('posts');
Route::any('/posts/{action}/{id?}',[PostsController::class,'postsAction'])->name('postsAction');
// Posts Route End



// Services Categories Route
Route::get('/listings/categories',[AdminController::class,'servicesCategories'])->name('servicesCategories');
Route::any('/listings/categories/{action}/{id?}',[AdminController::class,'servicesCategoriesAction'])->name('servicesCategoriesAction');
// Services Categories Route End

//Services Management
Route::get('/listings/sellers',[AdminController::class,'servicesSellers'])->name('servicesSellers');
Route::get('/listings/sellers/{id}',[AdminController::class,'servicesSeller'])->whereNumber('id')->name('servicesSeller');
Route::get('/listings',[AdminController::class,'services'])->name('services');
Route::any('/listings/{action}/{id?}',[AdminController::class,'servicesAction'])->name('servicesAction');
//Services Management End

// Posts Route
Route::get('/clients',[AdminController::class,'clients'])->name('clients');
Route::any('/clients/{action}/{id?}',[AdminController::class,'clientsAction'])->name('clientsAction');
// Posts Route End

// Brands Route
Route::get('/brands',[AdminController::class,'brands'])->name('brands');
Route::any('/brands/{action}/{id?}',[AdminController::class,'brandsAction'])->name('brandsAction');
// Brands Route End

// Slider Route
Route::get('/sliders',[AdminController::class,'sliders'])->name('sliders');
Route::any('/sliders/{action}/{id?}',[AdminController::class,'slidersAction'])->name('slidersAction');
Route::any('/sliders/slide/{action}/{id}',[AdminController::class,'slideAction'])->name('slideAction');
// Slider Route End

// Buyer Enquiries (RFQ) review
Route::get('/enquiries',[EnquiriesController::class,'index'])->name('enquiries');
Route::post('/enquiries/{id}/approve',[EnquiriesController::class,'approve'])->name('enquiriesApprove');
Route::post('/enquiries/{id}/reject',[EnquiriesController::class,'reject'])->name('enquiriesReject');
// Buyer Enquiries End

// Market Prices Route
Route::get('/market-prices',[MarketPricesController::class,'index'])->name('marketPrices');
Route::post('/market-prices/save/{id?}',[MarketPricesController::class,'save'])->name('marketPricesSave');
Route::post('/market-prices/delete/{id}',[MarketPricesController::class,'delete'])->name('marketPricesDelete');
// Market Prices Route End

// Gallery Route
Route::get('/galleries',[AdminController::class,'galleries'])->name('galleries');
Route::any('/galleries/{action}/{id?}',[AdminController::class,'galleriesAction'])->name('galleriesAction');
// Gallery Route End


// Theme Route
Route::get('/theme-setting',[AdminController::class,'themeSetting'])->name('themeSetting');
// Theme Route End

// Menus Route
Route::get('/menus',[MenusController::class,'menus'])->name('menus');
Route::any('/menus/config/{action}/{id?}',[MenusController::class,'menusAction'])->name('menusAction');
Route::post('/menus/items/post/{id}',[MenusController::class,'menusItemsPost'])->name('menusItemsPost');
Route::any('/menus/items/{action}/{id}',[MenusController::class,'menusItemsAction'])->name('menusItemsAction');
// Menus Route End


//User Management
Route::get('/users/admin/',[AdminController::class,'usersAdmin'])->name('usersAdmin');
Route::any('/users/admin/{action}/{id?}',[AdminController::class,'usersAdminAction'])->name('usersAdminAction');

Route::get('/users/customer/',[AdminController::class,'usersCustomer'])->name('usersCustomer');
Route::any('/users/customer/{action}/{id?}',[AdminController::class,'usersCustomerAction'])->name('usersCustomerAction');

Route::get('/users/roles',[AdminController::class,'userRoles'])->name('userRoles');
Route::any('/users/roles/{action}/{id?}',[AdminController::class,'userRoleAction'])->name('userRoleAction');

Route::get('/subscribes',[AdminController::class,'subscribes'])->name('subscribes');



// Apps Setting
Route::get('/setting/{type}',[AdminController::class,'setting'])->name('setting');
Route::post('/setting/{type}/update',[AdminController::class,'settingUpdate'])->name('settingUpdate');

});