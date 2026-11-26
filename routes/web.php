<?php

use App\Http\Controllers\Admin\CrewManagementController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetRequestController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return redirect()->route('login');
});

use App\Http\Controllers\CrewController;
use App\Http\Controllers\HomeController;
use App\Models\Crew;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/password-reset-request', [PasswordResetRequestController::class, 'create'])
    ->name('password.request.form');

Route::post('/password-reset-request', [PasswordResetRequestController::class, 'store'])
    ->name('password.request.submit');


Route::middleware('auth')->group(function () {
    Route::get('/crews/create', [CrewController::class, 'create'])
        ->name('crews.create');

    Route::post('/crews', [CrewController::class, 'store'])
        ->name('crews.store');
    
    Route::put('/crews/{crew}/update', [CrewController::class, 'update'])->name('crews.update');

    Route::get('/crews/{crew}', [CrewController::class, 'show'])
        ->name('crews.show');

    Route::get('/crews/{crew}/documents/create', [CrewController::class, 'createDocument'])
    ->name('crews.create.document');

    Route::post('/crews/{crew}/documents/store', [CrewController::class, 'storeDocument'])
    ->name('crews.store.document');

    Route::get('/crews/{crew}/service/record/create', [CrewController::class, 'createServiceRecored'])
    ->name('crews.create.service.recored');

    Route::post('/crews/{crew}/service/record/store', [CrewController::class, 'storeServiceRecored'])
    ->name('crews.store.service.recored');

    Route::get('/crews/{crew}/stcw/create', [CrewController::class, 'createStcw'])
    ->name('crews.create.stcw');

    Route::post('/crews/{crew}/stcw/store', [CrewController::class, 'storeStcw'])
    ->name('crews.store.stcw');

    Route::get('/crews/{crew}/contact/create', [CrewController::class, 'createContact'])
    ->name('crews.create.contact');

    Route::post('/crews/{crew}/contact/store', [CrewController::class, 'storeContact'])
    ->name('crews.store.contact');

    Route::get('/crews/{crew}/preview', [CrewController::class, 'view'])
    ->name('crews.view');
     Route::get('/crew/{crew}/correction',[CrewController::class, 'crewCorrection'])->name('crews.correction');

    Route::get('/crew/{crew}/document/view', [HomeController::class, 'documentView'])->name('crew.document.view');
    Route::get('/crew/{crew}/sea/service/view', [HomeController::class, 'seaServiceView'])->name('crew.sea.service.view');
    Route::get('/crew/{crew}/stcw/view', [HomeController::class, 'stcwView'])->name('crew.stcw.view');
   
    Route::get('/account', [HomeController::class, 'account'])->name('account');
    Route::post('/account/phone/number/update', [HomeController::class, 'phoneUpdate'])->name('account.phone.update');
    Route::post('/account/password.update', [HomeController::class, 'passwordUpdate'])->name('account.password.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/crews/index',[CrewManagementController::class, 'index'])->name('crews.index');
    
    Route::get('/crews/view/{crew}',[CrewManagementController::class,'view'])->name('crews.view');
    Route::get('/crews/{crew}/print',[CrewManagementController::class, 'print'])->name('crews.print');
    Route::get('/crews/cv/correction/request/{crew}', [CrewManagementController::class, 'correctionRequest'])->name('crews.correction');
    Route::post('/crew/cv/correction/request/{crew}/stoe', [CrewManagementController::class, 'correctionStore'])->name('crews.correction.store');

    Route::get('/crews/corrections/list', [CrewManagementController::class, 'crewCorrectionsList'])->name('crews.correction.list');


    Route::get('/notifications/{id}/read', function ($id) {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return redirect()->to($notification->data['url']);
    })->name('notifications.read');
});