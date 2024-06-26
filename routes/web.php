<?php


use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index')->name('home');

    // Users
    Route::get('/profile', 'UserController@profile')->name('users.profile');
    Route::post('/profile/update', 'UserController@updateProfile')->name('users.profile.update');

    // Boards
    Route::post('/sync-users', [App\Http\Controllers\BoardController::class, 'syncUsers'])->name('boards.syncUsers');
    Route::post('/boards/{board}/leave', [App\Http\Controllers\BoardController::class, 'leaveBoard'])->name('boards.leave');
    Route::resource('boards', BoardController::class);

    // Phases
    Route::get('/phases/get-phase-cards', [App\Http\Controllers\PhaseController::class, 'getCardsComponent'])->name('phases.getCardsComponent');
    Route::resource('phases', PhaseController::class);

    // Cards
    Route::get('/cards/getData', [App\Http\Controllers\CardController::class, 'getCardData'])->name('cards.getData');
    Route::post('/cards/update-phase', [App\Http\Controllers\CardController::class, 'updatePhase'])->name('cards.updatePhase');
    Route::post('/cards/update-members', [App\Http\Controllers\CardController::class, 'updateCardMembers'])->name('cards.updateCardMembers');
    Route::post('/cards/add-comment', [App\Http\Controllers\CardController::class, 'addComment'])->name('cards.addComment');
    Route::post('/cards/add-new-label', [App\Http\Controllers\CardController::class, 'addNewLabel'])->name('cards.addNewLabel');
    Route::post('/cards/add-due-date', [App\Http\Controllers\CardController::class, 'addDueDate'])->name('cards.addDueDate');
    Route::post('/cards/add-images', [App\Http\Controllers\CardController::class, 'addImages'])->name('cards.addImages');
    Route::post('/cards/add-attachment', [App\Http\Controllers\CardController::class, 'addAttachment'])->name('cards.addAttachment');
    Route::resource('cards', CardController::class);

    // Notifications
    Route::post('/notifications/viewed', [App\Http\Controllers\NotificationController::class, 'viewed'])->name('notifications.viewed');
    Route::get('notifications/new', [App\Http\Controllers\NotificationController::class, 'newNotifications'])->name('notifications.new');

    // Labels
    Route::resource('labels', LabelController::class);


    Route::group(['prefix' => 'dashboard','middleware' => 'admin'], function () {

        Route::get('/', 'HomeController@dashboard')->name('dashboard.index');

        // Users
        Route::post('users/table', [\App\Http\Controllers\UserController::class, 'table']) -> name('users.table');
        Route::resource('users', UserController::class);

        // Teams
        Route::post('teams/table', [\App\Http\Controllers\TeamController::class, 'table']) -> name('teams.table');
        Route::resource('teams', TeamController::class);

        // Events
        Route::post('events/table', [\App\Http\Controllers\EventController::class, 'table']) -> name('events.table');

        // Notifications
        Route::post('notifications/table', [\App\Http\Controllers\NotificationController::class, 'table']) -> name('notifications.table');

        // Boards
        Route::post('boards/table', [\App\Http\Controllers\BoardController::class, 'table']) -> name('boards.table');

    });

});
