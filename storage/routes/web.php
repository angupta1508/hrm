<?php

use App\Http\Controllers\Backend\RolesController;

use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\ProductsController;
use App\Http\Controllers\Backend\BanksController;
use App\Http\Controllers\Backend\ReportsController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\ProductCategoriesController;
use App\Http\Controllers\Backend\GiftController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\ReviewsController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\BlogsController;
use App\Http\Controllers\Backend\OffersController;
use App\Http\Controllers\Backend\NotificationsController;
use App\Http\Controllers\Backend\CoverImagesController;
use App\Http\Controllers\Backend\BannerCategoryController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\WalletController;
use App\Http\Controllers\Backend\AstrologersController;
use App\Http\Controllers\Backend\SkillController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Backend\NoticesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users;



function pr($arr)
{
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
}

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


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::group(['middleware' => 'auth'], function () {
});
