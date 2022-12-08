<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

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
// General app settings.
Route::get('/', 'HomeController@index')->name('home')->middleware(['auth']);
Route::get('home', 'HomeController@index')->middleware(['auth']);
Route::get('settings', 'AppSettingsController@settingsOverview')->name('settings')->middleware(['auth']);
Route::post('settings/re-generate-app-id', 'AppSettingsController@generateAppKey')->name('re-generate-app-id')->middleware(['auth']);

// User settings routes.
Route::get('user', 'UserSettingsController@userSettings')->name('user')->middleware(['auth']);
Route::post('user/update', 'UserSettingsController@updateUser')->name('user-update')->middleware(['auth']);
Route::get('/user/{user_id}/notifications', 'NotificationController@get')->name('user-notifications')->middleware(['auth']);
Route::get('/user/{user_id}/notifications/clear', 'NotificationController@clear')->name('user-notifications-clear')->middleware(['auth']);
Route::get('/user/{user_id}/notifications/unread', 'NotificationController@getUnread')->name('user-notifications-unread')->middleware(['auth']);

// Auth routes.
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Form/Submission routes.
Route::get('view/form/{id}', 'FormController@view')->name('view-form')->middleware(['auth']);
Route::get('view/form/{id}/status/change', 'FormController@statusChange')->name('status-change-form')->middleware(['auth']);
Route::get('view/form/{id}/delete', 'FormController@showDeleteForm')->name('show-delete-form')->middleware(['auth']);
Route::post('delete/form/{id}', 'FormController@deleteForm')->name('delete-form')->middleware(['auth']);
Route::get('view/form/{id}/submission/{submission_id}', 'SubmissionController@view')->name('view-submission')->middleware(['auth']);
Route::get('view/form/{id}/export', 'FormController@exportSubmissions')->middleware(['auth']);
Route::get('view/form/{id}/events', 'FormController@showFormEvents')->middleware(['auth']);
Route::get('download/file/{id}', 'FormController@downloadFile')->middleware(['auth']);
Route::post('view/form/{id}/options/save', 'FormController@saveOptions')->name('save-form-options')->middleware(['auth']);
Route::post('api/submission/search', 'SubmissionController@search')->name('submission-search')->middleware(['auth']);
Route::post('api/submission/delete', 'SubmissionController@delete')->name('submission-delete')->middleware(['auth']);

/// Events routes.
Route::get('events', 'EventsController@view')->name('events')->middleware(['auth']);
Route::get('events/delete', 'EventsController@delete')->name('events-delete')->middleware(['auth']);

/// Advance search routes.
Route::get('search', 'AdvanceSearchController@view')->name('advance-search')->middleware(['auth']);
Route::post('api/advance-search', 'AdvanceSearchController@search')->name('advance-search-api')->middleware(['auth']);

// Submission URL.
Route::post('/submission', 'SubmissionController@store')->name('submission')->middleware('submission');

// Form handlers.
Route::post('form/{form_id}/handler/save/{handler}', [
    'uses' => 'FormHandlerController@saveHandlerForm'
])->where('handler', '([A-Za-z0-9\-\/]+)')->name('handler-form')->middleware(['auth']);
