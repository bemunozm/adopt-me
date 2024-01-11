<?php

use App\Http\Controllers\AdopterController;
use Illuminate\Support\Facades\Route;

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

Route::get('/hola', function () {
    return view('welcome');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\VisitController;
use App\Models\Adopter;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\Vaccine;

Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');
	Route::resource('/organization', OrganizationController::class)->middleware('auth');
	Route::resource('/sites', SiteController::class)->middleware('auth');
	Route::resource('/pet', PetController::class)->middleware('auth');
	Route::resource('/vaccine', VaccineController::class)->middleware('auth');
	Route::resource('/visit', VisitController::class)->middleware('auth');
	Route::post('/visits/{visit}/change-status', [VisitController::class, 'changeStatus'])->name('visit.changeStatus');


	Route::post('/pet/{pet_id}', [PetController::class, 'adopt'])->middleware('auth')->name('pet.adopt');
	Route::get('/pet-filter', [FilterController::class, 'petFilter'])->name('pet.filter');

	Route::get('/donate/{id}', [PetController::class, 'show'])->middleware('auth')->name('donate.show');
	Route::get('/guest-show/{id}', [CollectionController::class, 'guestShow'])->middleware('guest')->name('guest.show');
	Route::get('/guest-donate/{id}', [PetController::class, 'guestDonate'])->middleware('guest')->name('guest.donate');
	Route::get('/my-pet', [PetController::class, 'mypet'])->middleware('auth')->name('my.pet');

	Route::post('/donate/{id}', [CollectionController::class, 'donate'])->middleware('auth')->name('collection.donate');
	Route::post('/approve/{id}', [CollectionController::class, 'approve'])->middleware('auth')->name('collection.approve');
	Route::get('/collection/{id}/approve', [CollectionController::class, 'approve'])->name('collection.filter');
	Route::post('/collection/changeStatus', [CollectionController::class, 'changeStatus'])->middleware('auth')->name('collection.changeStatus');

	Route::post('/adopter-approve/{id}', [AdopterController::class, 'approve'])->middleware('auth')->name('adopter.approve');
	Route::get('/adopter-approve/{id}/approve', [AdopterController::class, 'approve'])->name('adopter.filter');
	Route::post('/adopter/changeStatus', [AdopterController::class, 'changeStatus'])->middleware('auth')->name('adopter.changeStatus');

	Route::resource('/image', ImageController::class)->middleware('auth');  
	Route::resource('/adopter', AdopterController::class)->middleware('auth');
	Route::resource('/collection', CollectionController::class)->middleware('auth');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/index', function () {
		return view('welcome');
	})->middleware('guest')->name('index');
	Route::get('/pets', [PetController::class, 'guestIndex'])->middleware('guest')->name('pets');
	Route::get('/collections', [CollectionController::class, 'guestIndex'])->middleware('guest')->name('collections');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});