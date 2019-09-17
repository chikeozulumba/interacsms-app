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

use Carbon\Carbon;
use App\Events\MessageProcessed;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/datta', function () {
//     $now = \Carbon\Carbon::now()->addHours(1)->format('Y-m-d H:i');
//     $messages = \App\Models\Message::where(function ($query) {
//         $now = \Carbon\Carbon::now()->addHours(1)->format('Y-m-d H:i');
//         $query->where('brodcast_time', '=', (string) $now)
//         ->orWhere('brodcast_time', '=', "None");
//     })
//     ->where('permission', "ALLOW")
//     ->where('sent', 0)
//     ->where('isDeleted', 0)
//     ->orderBy('updated_at', 'asc')
//     ->get();

//     return [$messages, $now];
// });
Route::get('/datta', function () {
    $p_message = "Your message could not be sent.\nKindly check your if your units are sufficient";
    $p_time = Carbon::now()->addHours(1);
    $payload = [
        "message" => $p_message,
        "time_log" => $p_time,
        "title" => "MESSAGE FAILED",
        'type' => "MESSAGE FAILED",
        'user_id' => Auth::user()->id,
        'message_id' => '0240f840-a216-11e8-ada7-c38d5ae8f388'
    ];
    $addMessageNotification = (new \App\Jobs\AddMessageNotification($payload))->onQueue('message_notifications');
    dispatch($addMessageNotification);

    return 'yes';
});

Route::middleware(['guest'])->group(function () {
    // Authentication Routes...
    $this->get('auth', 'Auth\LoginController@showLoginForm')->name('auth');
    $this->post('auth', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('/auth/reset', 'Auth\ForgotPasswordController@reset')->name('reset.password');
    Route::post('/auth/update/password', 'Auth\ForgotPasswordController@updatePassword')->name('update.password');
    Route::get('/auth/reset/{token}', 'Auth\ForgotPasswordController@passwordReset')->name('auth.password_reset');
});

Route::middleware(['auth', 'if.auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/verify/message', 'Main\IndexController@verifyMessage')->name('verify.message');
    Route::post('/send/message', 'Main\IndexController@saveMessage')->name('save.message');
    Route::post('/draft/message', 'Main\IndexController@draftMessage')->name('draft.message');
    Route::get('/draft/message', 'Main\IndexController@getDraftMessage')->name('get.drafts');
    Route::get('/get/message', 'Main\IndexController@getMessage')->name('get.messages');
    Route::get('/get/schedule-message', 'Main\IndexController@getScheduledMessage')->name('get.scheduled.messages');
    Route::get('/get/sent-message', 'Main\IndexController@getSentMessage')->name('get.sent.record');
    Route::get('/delete/message', 'Main\IndexController@deleteMessage')->name('delete.message');
    Route::get('/delete/draft', 'Main\IndexController@deleteDraft')->name('delete.draft');
    Route::get('/delete/contact', 'Main\IndexController@deleteContact')->name('delete.contact');
    Route::get('/delete/group', 'Main\IndexController@deleteGroup')->name('delete.group');
    Route::get('/single/contact', 'Main\IndexController@getSingleContact')->name('single.contact');
    Route::post('/update/contact', 'Main\IndexController@updateSingleContact')->name('update.contact');
    Route::post('/add/contact', 'Main\IndexController@addNewContact')->name('add.new.contact');
    Route::post('/add/group', 'Main\IndexController@addNewGroup')->name('add.new.group');
    Route::get('/edit/group', 'Main\IndexController@editGroup')->name('edit.group');
    Route::post('/update/group', 'Main\IndexController@updateGroup')->name('update.group');
    Route::get('/notifications', 'Main\IndexController@closeNotifications')->name('close.notifications');

    // USER
    Route::prefix('user')->group(function () {
        Route::get('profile', 'User\UserController@getProfile')->name('get.profile');
        Route::post('profile', 'User\UserController@updateProfile')->name('update.profile');
    });

    // AUTH
    Route::prefix('auth')->group(function () {
        Route::post('payment/make', 'Main\IndexController@redirectToGateway')->name('make.payment');
        Route::get('payment/finalize', 'Main\IndexController@handleGatewayCallback')->name('final.payment');
        Route::get('payments', 'Main\IndexController@getPayments')->name('get.payments');

        // UPDATE META DATA ON FRONT END
        Route::get('meta/update', 'User\ViewController@updateViewMeta')->name('meta.update');
        Route::get('data/contacts', 'User\ViewController@getDataForContacts')->name('view.contacts.data');
    });
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('settings', 'User\UserController@settings')->name('settings');
    Route::get('verify', 'Auth\VerifyController@index')->name('auth.verify');
    Route::get('verify/email', 'Auth\VerifyController@doVerify')->name('auth.verify.email');
    Route::get('/verify/{token}', 'Auth\VerifyController@validateEmail')->name('auth.verify_email');
});

Route::get('dummy', function () { });
