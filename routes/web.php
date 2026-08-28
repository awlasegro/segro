<?php

use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\OrderListController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferenceCodesController;
use App\Http\Controllers\UserValletController;
use App\Http\Controllers\PlatformWalletController;
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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::middleware('admin')->group(function () {
    // Administration Routes
    Route::get('administration', [MembersController::class, 'index'])->name('administration');
    Route::get('admin.dashboard', [MembersController::class, 'dashboard']);
    Route::get('add-member', [MembersController::class, 'create']);
    Route::post('add-member', [MembersController::class, 'store']);
    Route::get('update-user/{id}', [MembersController::class, 'edit']);
    Route::post('update-members/{id}', [MembersController::class, 'update'])->name('members.update');
    Route::get('reset-todays-orders/{user}', [MembersController::class, 'resetTodaysOrders'])->name('reset.todays.orders');
    Route::get('generate-orders/{id}', [MembersController::class, 'generateOrders'])->name('generate.orders');
    Route::get('order-queue/{id}', [MembersController::class, 'orderQueue'])->name('order.queue');
    Route::post('order-queue/{id}/update', [MembersController::class, 'updateQueuedOrder'])->name('order.queue.update');
    Route::post('save_selected_orders/{user}', [MembersController::class, 'saveSelectedOrders'])->name('save_selected_orders');
    Route::get('reference-codes', [ReferenceCodesController::class, 'index'])->name('reference-codes.index');
    Route::get('add-reference-code', [ReferenceCodesController::class, 'create']);
    Route::post('create-reference-code', [ReferenceCodesController::class, 'store']);
    Route::get('reference-codes/{id}/edit', [ReferenceCodesController::class, 'edit'])->name('reference-codes.edit');
    Route::post('reference-codes/{id}/update', [ReferenceCodesController::class, 'update'])->name('reference-codes.update');
    Route::post('reference-codes/{id}/delete', [ReferenceCodesController::class, 'destroy'])->name('reference-codes.destroy');
    Route::post('reference-codes/generate', [ReferenceCodesController::class, 'generate'])->name('reference-codes.generate');
    Route::get('memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('add-memberships', [MembershipController::class, 'create']);
    Route::post('create-memberships', [MembershipController::class, 'store']);
    Route::get('memberships/{id}/edit', [MembershipController::class, 'edit'])->name('memberships.edit');
    Route::post('memberships/{id}/update', [MembershipController::class, 'update'])->name('memberships.update');
    Route::post('memberships/{id}/delete', [MembershipController::class, 'destroy'])->name('memberships.destroy');
    Route::get('reset-single/{id}', [MembersController::class, 'reset_single_order'])->name('reset.single');
    Route::get('reset-orders/{id}', [MembersController::class, 'reset_orders'])->name('reset.orders');
    Route::post('update-orders/{id}', [MembersController::class, 'update_orders'])->name('update.orders');
    Route::get('add-debit/{id}', [FundsController::class, 'index']);
    Route::post('add-debit', [FundsController::class, 'store']);
    Route::get('order-list', [OrderListController::class, 'index'])->name('order-list.index');
    Route::get('add-order-list', [OrderListController::class, 'create']);
    Route::post('create-orders-list', [OrderListController::class, 'store']);
    Route::get('order-list/{id}/edit', [OrderListController::class, 'edit'])->name('order-list.edit');
    Route::post('order-list/{id}/update', [OrderListController::class, 'update'])->name('order-list.update');
    Route::post('order-list/{id}/delete', [OrderListController::class, 'destroy'])->name('order-list.destroy');
    Route::get('vallet-information/{id}', [UserValletController::class, 'index']);
    Route::post('update-wallet', [UserValletController::class, 'show'])->name('update.wallet');
    Route::post('update-wallet-info', [UserValletController::class, 'update'])->name('update.wallet.info');
    Route::get('add-vallet-information/{id}', [UserValletController::class, 'create']);
    Route::post('save-vallet', [UserValletController::class, 'store']);
    Route::get('user-recharge-history/{id}', [MembersController::class, 'user_recharge_history'])->name('user.recharge.history');
    
    // Funds Approval Routes
    Route::get('/admin/deposit-requests', [FundsController::class, 'depositRequests'])->name('admin.deposit.requests');
    Route::get('/admin/redemption-requests', [FundsController::class, 'redemptionRequests'])->name('admin.redemption.requests');
    Route::post('/admin/funds-request/{id}/approve', [FundsController::class, 'approveRequest'])->name('admin.funds.approve');
    Route::post('/admin/funds-request/{id}/reject', [FundsController::class, 'rejectRequest'])->name('admin.funds.reject');

    // Platform Wallet Settings Routes
    Route::get('/admin/platform-wallet', [PlatformWalletController::class, 'edit'])->name('admin.platform.wallet');
    Route::post('/admin/platform-wallet', [PlatformWalletController::class, 'update'])->name('admin.platform.wallet.update');

    // Support Chat Routes
    Route::get('admin/chats', [AdminChatController::class, 'index'])->name('admin.chats.index');
    Route::get('admin/chats/{user}', [AdminChatController::class, 'show'])->name('admin.chats.show');
    Route::get('admin/chats/{user}/messages', [AdminChatController::class, 'fetch'])->name('admin.chats.fetch');
    Route::get('admin/chats/{user}/panel', [AdminChatController::class, 'panel'])->name('admin.chats.panel');
    Route::post('admin/chats/{user}/send', [AdminChatController::class, 'send'])->name('admin.chats.send');
});
// Admin Authentication Routes
Route::get('admin-login', [MembersController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin-login', [MembersController::class, 'login']);
Route::get('admin-logout', [MembersController::class, 'logout'])->name('admin.logout');

// User Routes
Route::view('/', 'user.index')->middleware('redirect.auth');
Route::get('dashboard', [ProfileController::class, 'dashboard'])->name('dashboard')->middleware('auth');
// Route::view('support', 'user.support')->name('support')->middleware('auth');
Route::view('user-login', 'user.signin')->name('user.login')->middleware('redirect.auth');
Route::view('user-register', 'user.register')->middleware('redirect.auth');
Route::view('membership', 'user.membership')->middleware('auth');
Route::post('user-registeration', [ProfileController::class, 'userRegisteration'])->name('user-registeration');
Route::get('profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
Route::get('data-optimization', [ProfileController::class, 'show'])->name('data-optimization')->middleware('auth');
Route::get('history', [OrdersController::class, 'history'])->name('history')->middleware('auth');
Route::get('generate-order', [OrdersController::class, 'index'])->name('generate.order')->middleware('auth');
Route::post('process-order', [OrdersController::class, 'processOrder'])->name('process.order')->middleware('auth');
Route::match(['get', 'post'], 'support', [OrdersController::class, 'support'])->name('support');
Route::get('wallet-information', [ProfileController::class, 'showWalletInformation'])->name('wallet-information')->middleware('auth');
Route::post('wallet-information', [ProfileController::class, 'storeWalletInformation'])->name('wallet-information.save')->middleware('auth');
Route::view('company-information', 'user.company-information')->name('company-information')->middleware('auth');
Route::get('invitation', [ProfileController::class, 'invitation'])->name('invitation')->middleware('auth');
Route::view('security', 'user.security')->name('security')->middleware('auth');
Route::view('termsconditions', 'user.termsconditions')->name('termsconditions')->middleware('auth');
Route::view('faq', 'user.faq')->name('faq')->middleware('auth');
Route::get('recharge', [ProfileController::class, 'showBalanceinRecharge'])->name('recharge')->middleware('auth');
Route::post('recharge', [ProfileController::class, 'submitRecharge'])->name('recharge.submit')->middleware('auth');
Route::get('redemption', [ProfileController::class, 'showRedemption'])->name('redemption')->middleware('auth');
Route::get('user.logout', [ProfileController::class, 'logout'])->name('user.logout')->middleware('auth');
Route::post('/change-login-password', [ProfileController::class, 'changeLoginPassword'])->name('change.login.password')->middleware('auth');
Route::post('/change-wallet-password', [ProfileController::class, 'changeWalletPassword'])->name('change.wallet.password')->middleware('auth');
Route::post('/redemption', [ProfileController::class, 'redeem'])->name('redeem')->middleware('auth');
Route::get('/recharge-history', [ProfileController::class, 'rechargeHistory'])->name('recharge.history')->middleware('auth');
Route::get('/redemption-history', [ProfileController::class, 'redemptionHistory'])->name('redemption.history')->middleware('auth');

// Support Chat Routes
Route::get('chat/messages', [ChatController::class, 'fetch'])->name('chat.fetch')->middleware('auth');
Route::post('chat/send', [ChatController::class, 'send'])->name('chat.send')->middleware('auth');
