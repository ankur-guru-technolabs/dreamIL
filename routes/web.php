<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CssController;
use App\Http\Controllers\BasicUiController;
use App\Http\Controllers\AdvanceUiController;
use App\Http\Controllers\ExtraComponentsController;
use App\Http\Controllers\BasicTableController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ChartController;

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
Auth::routes(['verify' => true]);

// Dashboard Route
// Route::get('/', [DashboardController::class, 'dashboardModern'])->middleware('verified');
Route::post('post_login', ['uses' => 'App\Http\Controllers\AuthController@postLogin']);

Route::group(['middleware' => 'auth'], function () {

Route::get('/', [DashboardController::class, 'dashboardEcommerce']);

Route::get('/modern', [DashboardController::class, 'dashboardModern']);
Route::get('/ecommerce', [DashboardController::class, 'dashboardEcommerce']);
Route::get('/analytics', [DashboardController::class, 'dashboardAnalytics']);

// Application Route
Route::get('/app-email', [ApplicationController::class, 'emailApp']);
Route::get('/app-email/content', [ApplicationController::class, 'emailContentApp']);
Route::get('/app-chat', [ApplicationController::class, 'chatApp']);
Route::get('/app-todo', [ApplicationController::class, 'todoApp']);
Route::get('/app-kanban', [ApplicationController::class, 'kanbanApp']);
Route::get('/app-file-manager', [ApplicationController::class, 'fileManagerApp']);
Route::get('/app-contacts', [ApplicationController::class, 'contactApp']);
Route::get('/app-calendar', [ApplicationController::class, 'calendarApp']);
Route::get('/app-invoice-list', [ApplicationController::class, 'invoiceList']);
Route::get('/app-invoice-view', [ApplicationController::class, 'invoiceView']);
Route::get('/app-invoice-edit', [ApplicationController::class, 'invoiceEdit']);
Route::get('/app-invoice-add', [ApplicationController::class, 'invoiceAdd']);
Route::get('/eCommerce-products-page', [ApplicationController::class, 'ecommerceProduct']);
Route::get('/eCommerce-pricing', [ApplicationController::class, 'eCommercePricing']);

// User profile Route
Route::get('/user-profile-page', [UserProfileController::class, 'userProfile']);

// Page Route
Route::get('/page-contact', [PageController::class, 'contactPage']);
Route::get('/page-blog-list', [PageController::class, 'pageBlogList']);
Route::get('/page-search', [PageController::class, 'searchPage']);
Route::get('/page-knowledge', [PageController::class, 'knowledgePage']);
Route::get('/page-knowledge/licensing', [PageController::class, 'knowledgeLicensingPage']);
Route::get('/page-knowledge/licensing/detail', [PageController::class, 'knowledgeLicensingPageDetails']);
Route::get('/page-timeline', [PageController::class, 'timelinePage']);
Route::get('/page-faq', [PageController::class, 'faqPage']);
Route::get('/page-faq-detail', [PageController::class, 'faqDetailsPage']);
Route::get('/page-account-settings', [PageController::class, 'accountSetting']);
Route::get('/page-blank', [PageController::class, 'blankPage']);
Route::get('/page-collapse', [PageController::class, 'collapsePage']);

// Media Route
Route::get('/media-gallery-page', [MediaController::class, 'mediaGallery']);
Route::get('/media-hover-effects', [MediaController::class, 'hoverEffect']);

// User Route
Route::get('/page-users-list', [UserController::class, 'usersList']);
Route::get('/page-users-view', [UserController::class, 'usersView']);
Route::get('/page-users-edit', [UserController::class, 'usersEdit']);

// Authentication Route
Route::get('/user-login', [AuthenticationController::class, 'userLogin']);
Route::get('/user-register', [AuthenticationController::class, 'userRegister']);
Route::get('/user-forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::get('/user-lock-screen', [AuthenticationController::class, 'lockScreen']);

// Misc Route
Route::get('/page-404', [MiscController::class, 'page404']);
Route::get('/page-maintenance', [MiscController::class, 'maintenancePage']);
Route::get('/page-500', [MiscController::class, 'page500']);

// Card Route
Route::get('/cards-basic', [CardController::class, 'cardBasic']);
Route::get('/cards-advance', [CardController::class, 'cardAdvance']);
Route::get('/cards-extended', [CardController::class, 'cardsExtended']);

// Css Route
Route::get('/css-typography', [CssController::class, 'typographyCss']);
Route::get('/css-color', [CssController::class, 'colorCss']);
Route::get('/css-grid', [CssController::class, 'gridCss']);
Route::get('/css-helpers', [CssController::class, 'helpersCss']);
Route::get('/css-media', [CssController::class, 'mediaCss']);
Route::get('/css-pulse', [CssController::class, 'pulseCss']);
Route::get('/css-sass', [CssController::class, 'sassCss']);
Route::get('/css-shadow', [CssController::class, 'shadowCss']);
Route::get('/css-animations', [CssController::class, 'animationCss']);
Route::get('/css-transitions', [CssController::class, 'transitionCss']);

// Basic Ui Route
Route::get('/ui-basic-buttons', [BasicUiController::class, 'basicButtons']);
Route::get('/ui-extended-buttons', [BasicUiController::class, 'extendedButtons']);
Route::get('/ui-icons', [BasicUiController::class, 'iconsUI']);
Route::get('/ui-alerts', [BasicUiController::class, 'alertsUI']);
Route::get('/ui-badges', [BasicUiController::class, 'badgesUI']);
Route::get('/ui-breadcrumbs', [BasicUiController::class, 'breadcrumbsUI']);
Route::get('/ui-chips', [BasicUiController::class, 'chipsUI']);
Route::get('/ui-chips', [BasicUiController::class, 'chipsUI']);
Route::get('/ui-collections', [BasicUiController::class, 'collectionsUI']);
Route::get('/ui-navbar', [BasicUiController::class, 'navbarUI']);
Route::get('/ui-pagination', [BasicUiController::class, 'paginationUI']);
Route::get('/ui-preloader', [BasicUiController::class, 'preloaderUI']);

// Advance UI Route
Route::get('/advance-ui-carousel', [AdvanceUiController::class, 'carouselUI']);
Route::get('/advance-ui-collapsibles', [AdvanceUiController::class, 'collapsibleUI']);
Route::get('/advance-ui-toasts', [AdvanceUiController::class, 'toastUI']);
Route::get('/advance-ui-tooltip', [AdvanceUiController::class, 'tooltipUI']);
Route::get('/advance-ui-dropdown', [AdvanceUiController::class, 'dropdownUI']);
Route::get('/advance-ui-feature-discovery', [AdvanceUiController::class, 'discoveryFeature']);
Route::get('/advance-ui-media', [AdvanceUiController::class, 'mediaUI']);
Route::get('/advance-ui-modals', [AdvanceUiController::class, 'modalUI']);
Route::get('/advance-ui-scrollspy', [AdvanceUiController::class, 'scrollspyUI']);
Route::get('/advance-ui-tabs', [AdvanceUiController::class, 'tabsUI']);
Route::get('/advance-ui-waves', [AdvanceUiController::class, 'wavesUI']);
Route::get('/fullscreen-slider-demo', [AdvanceUiController::class, 'fullscreenSlider']);

// Extra components Route
Route::get('/extra-components-range-slider', [ExtraComponentsController::class, 'rangeSlider']);
Route::get('/extra-components-sweetalert', [ExtraComponentsController::class, 'sweetAlert']);
Route::get('/extra-components-nestable', [ExtraComponentsController::class, 'nestAble']);
Route::get('/extra-components-treeview', [ExtraComponentsController::class, 'treeView']);
Route::get('/extra-components-ratings', [ExtraComponentsController::class, 'ratings']);
Route::get('/extra-components-tour', [ExtraComponentsController::class, 'tour']);
Route::get('/extra-components-i18n', [ExtraComponentsController::class, 'i18n']);
Route::get('/extra-components-highlight', [ExtraComponentsController::class, 'highlight']);

// Basic Tables Route
Route::get('/table-basic', [BasicTableController::class, 'tableBasic']);

// Data Table Route
Route::get('/table-data-table', [DataTableController::class, 'dataTable']);

// Form Route
Route::get('/form-elements', [FormController::class, 'formElement']);
Route::get('/form-select2', [FormController::class, 'formSelect2']);
Route::get('/form-validation', [FormController::class, 'formValidation']);
Route::get('/form-masks', [FormController::class, 'masksForm']);
Route::get('/form-editor', [FormController::class, 'formEditor']);
Route::get('/form-file-uploads', [FormController::class, 'fileUploads']);
Route::get('/form-layouts', [FormController::class, 'formLayouts']);
Route::get('/form-wizard', [FormController::class, 'formWizard']);

// Charts Route
Route::get('/charts-chartjs', [ChartController::class, 'chartJs']);
Route::get('/charts-chartist', [ChartController::class, 'chartist']);
Route::get('/charts-sparklines', [ChartController::class, 'sparklines']);


// locale route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
Route::get('user/view/{id}', ['uses' => 'App\Http\Controllers\UserController@userView']);
Route::resource('match_profile', 'App\Http\Controllers\MatchController', ['except' => ['show']]);
Route::get('match_profile/delete/{id}', ['uses' => 'App\Http\Controllers\MatchController@deleteMatchProfile']);
Route::get('free_plan', ['uses' => 'App\Http\Controllers\FreePlanController@getFreePlan']);
Route::post('update_free_plan', ['uses' => 'App\Http\Controllers\FreePlanController@updateFreePlan']);
Route::post('update_paid_plan', ['uses' => 'App\Http\Controllers\FreePlanController@updatePaidPlan']);
Route::get('paid_plan', ['uses' => 'App\Http\Controllers\FreePlanController@getPaidPlan']);
Route::get('subscription_orders', ['uses' => 'App\Http\Controllers\OrdersController@subscriptionOrders']);
Route::get('subscription_plan', ['uses' => 'App\Http\Controllers\OrdersController@subscriptionPlan']);
Route::get('subscription_plan/create', ['uses' => 'App\Http\Controllers\OrdersController@subscriptionPlanCreate']);
Route::post('subscription_plan/store', ['uses' => 'App\Http\Controllers\OrdersController@subscriptionPlanStore']);
Route::get('subscription_plan/edit/{id}', ['uses' => 'App\Http\Controllers\OrdersController@editSubscriptionPlan']);
Route::post('udpate_subscription_plan', ['uses' => 'App\Http\Controllers\OrdersController@udpateSubscriptionPlan']);
Route::get('orders/approve/{id}', ['uses' => 'App\Http\Controllers\OrdersController@approveOrder']);
Route::get('orders/reject/{id}', ['uses' => 'App\Http\Controllers\OrdersController@rejectOrder']);
Route::get('settings', ['uses' => 'App\Http\Controllers\SettingsController@settings']);
Route::post('update_settings', ['uses' => 'App\Http\Controllers\SettingsController@updateSettings']);
Route::get('privacy_policy', ['uses' => 'App\Http\Controllers\StaticPagesController@getPrivacyPolicy']);
Route::get('terms_and_conditions', ['uses' => 'App\Http\Controllers\StaticPagesController@getTermsAndConditions']);
Route::get('default_settings', ['uses' => 'App\Http\Controllers\SettingsController@getUserSettings']);
Route::post('update_user_settings', ['uses' => 'App\Http\Controllers\SettingsController@updateUserSettings']);
Route::post('update_privacy_policy', ['uses' => 'App\Http\Controllers\StaticPagesController@updatePrivacyPolicy']);
Route::post('update_terms_and_conditions', ['uses' => 'App\Http\Controllers\StaticPagesController@updateTermsAndConditions']);
Route::get('page/disclaimer', ['uses' => 'App\Http\Controllers\StaticPagesController@disclaimer']);
Route::get('page/privacy_and_cookie_policy', ['uses' => 'App\Http\Controllers\StaticPagesController@privacyAndCookiePolicy']);
Route::get('page/how_to_gethingd_processes_your_data', ['uses' => 'App\Http\Controllers\StaticPagesController@howToGethingdProccessesYourData']);
Route::resource('categories', 'App\Http\Controllers\CategoriesController', ['except' => ['show']]);
Route::get('categorie/delete/{id}', ['uses' => 'App\Http\Controllers\CategoriesController@deleteCategory']);
Route::get('delete_artical/delete/{id}', ['uses' => 'App\Http\Controllers\ArticalManagementController@deleteArtical']);
Route::get('delete_advertisement/{id}', ['uses' => 'App\Http\Controllers\AdvertisementController@deleteAdvertisement']);
Route::get('push_notification', ['uses' => 'App\Http\Controllers\NotificationController@pushNotification']);
Route::post('send_notifcation', ['uses' => 'App\Http\Controllers\NotificationController@sendNotifcation']);
Route::get('reports', ['uses' => 'App\Http\Controllers\UserController@userReports']);
Route::get('logout', ['uses' => 'App\Http\Controllers\AuthController@logout']);
Route::get('profile', ['uses' => 'App\Http\Controllers\AuthController@profile']);
Route::post('update_profile', ['uses' => 'App\Http\Controllers\AuthController@updateProfile']);
Route::resource('question', 'App\Http\Controllers\QuestionManamentController', ['except' => ['show']]);
Route::get('question/delete/{id}', ['uses' => 'App\Http\Controllers\QuestionManamentController@deleteQuestion']);
Route::post('question/set_default', ['uses' => 'App\Http\Controllers\QuestionManamentController@set_default']);

Route::get('advertisement', ['uses' => 'App\Http\Controllers\AdvertisementController@getAdvertisement']);
Route::get('advertisement/view/{id}', ['uses' => 'App\Http\Controllers\AdvertisementController@viewAdvertisement']);
Route::get('advertisement/view_details/{id}', ['uses' => 'App\Http\Controllers\AdvertisementController@viewAdvertisementDetails']);
Route::resource('artical', 'App\Http\Controllers\ArticalManagementController', ['except' => ['show']]);
Route::resource('artical_category', 'App\Http\Controllers\ArticalCategoryController', ['except' => ['show']]);
Route::resource('general_question', 'App\Http\Controllers\GeneralQuestionsController', ['except' => ['show']]);
Route::resource('advertise_category', 'App\Http\Controllers\AdvertisementCategoryController', ['except' => ['show']]);
Route::get('advertise_category/delete/{id}', ['uses' => 'App\Http\Controllers\AdvertisementCategoryController@deleteCategory']);
Route::get('general_question/delete/{id}', ['uses' => 'App\Http\Controllers\GeneralQuestionsController@deleteQuestion']);
Route::post('update_advertisement/{id}', ['uses' => 'App\Http\Controllers\AdvertisementController@updateAdvertisement']);
});
Route::get('page/privacy_policy', ['uses' => 'App\Http\Controllers\StaticPagesController@privacyPolicy']);
Route::get('page/term_and_condition', ['uses' => 'App\Http\Controllers\StaticPagesController@termAndCondition']);
Route::get('get_notification', ['uses' => 'App\Http\Controllers\NotificationController@getNotification']);

