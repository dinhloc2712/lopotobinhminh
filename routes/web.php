<?php

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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Post Frontend Routes (Moved to bottom to avoid conflicts)


// Universal Fallback Route for Missing Storage Symlink on Shared Hosting (handles all files in storage/app/public)
Route::get('storage/{path}', [\App\Http\Controllers\Admin\UserController::class, 'servePublicStorageFile'])->where('path', '.*')->name('storage.fallback');

// Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Registration
Route::post('register', [\App\Http\Controllers\CustomerController::class, 'register'])->name('register');

// Contact Form Submission
Route::post('/contacts', [\App\Http\Controllers\ContactController::class, 'store'])->name('contacts.store');

// Product Reviews (Frontend)
Route::post('/reviews', [\App\Http\Controllers\ProductReviewController::class, 'store'])->name('frontend.reviews.store');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.profile.show');
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::patch('users/{user}/role',       [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update_role');
    Route::patch('users/{user}/branch',     [\App\Http\Controllers\Admin\UserController::class, 'updateBranch'])->name('users.update_branch');

    // User Documents (Giấy tờ cá nhân) - Đã chuyển sang module Media chung

    // Role Management
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);




    // Profile Management
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // CRM Management
    Route::get('crm', [\App\Http\Controllers\Admin\CrmController::class, 'index'])->name('crm.index');


    // Secure Media Manager
    Route::get('media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
    Route::post('media/folder', [\App\Http\Controllers\Admin\MediaController::class, 'createFolder'])->name('media.create-folder');
    Route::post('media/map', [\App\Http\Controllers\Admin\MediaController::class, 'mapFile'])->name('media.map');
    Route::post('media/bulk-delete', [\App\Http\Controllers\Admin\MediaController::class, 'bulkDestroy'])->name('media.bulk-delete');
    Route::post('media/move', [\App\Http\Controllers\Admin\MediaController::class, 'moveItems'])->name('media.move');
    Route::post('media/copy', [\App\Http\Controllers\Admin\MediaController::class, 'copyItems'])->name('media.copy');
    Route::delete('media/{filename}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy')->where('filename', '.*');
    Route::get('media/{filename}/generate', [\App\Http\Controllers\Admin\MediaController::class, 'showGenerateForm'])->name('media.generate.form')->where('filename', '.*');
    Route::post('media/{filename}/generate', [\App\Http\Controllers\Admin\MediaController::class, 'generateDocument'])->name('media.generate')->where('filename', '.*');
    Route::post('media/{filename}/save-config', [\App\Http\Controllers\Admin\MediaController::class, 'saveConfig'])->name('media.save-config')->where('filename', '.*');

    // Finance Management
    Route::resource('finance', \App\Http\Controllers\Admin\FinanceController::class)->except(['show']);

    // News/Announcements Management
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);

    Route::get('news/{news}/download', [\App\Http\Controllers\Admin\NewsController::class, 'downloadAttachment'])->name('news.download');

    // Branches
    Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);

    // Chat System
    Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/users', [\App\Http\Controllers\Admin\ChatController::class, 'fetchUsers'])->name('chat.users');
    Route::get('chat/conversations', [\App\Http\Controllers\Admin\ChatController::class, 'fetchConversations'])->name('chat.conversations');
    Route::get('chat/conversations/{conversation}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('chat/conversations/{conversation}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('chat/conversations/start', [\App\Http\Controllers\Admin\ChatController::class, 'startConversation'])->name('chat.start');
    Route::post('chat/conversations/group', [\App\Http\Controllers\Admin\ChatController::class, 'startGroupChat'])->name('chat.start_group');
    Route::post('chat/conversations/{conversation}/add-member', [\App\Http\Controllers\Admin\ChatController::class, 'addMember'])->name('chat.add_member');
    Route::post('chat/conversations/{conversation}/remove-member', [\App\Http\Controllers\Admin\ChatController::class, 'removeMember'])->name('chat.remove_member');
    Route::patch('chat/conversations/{conversation}/name', [\App\Http\Controllers\Admin\ChatController::class, 'updateGroupChatName'])->name('chat.update_group_name');
    Route::delete('chat/conversations/{conversation}/group', [\App\Http\Controllers\Admin\ChatController::class, 'destroyGroupChat'])->name('chat.destroy_group');

    Route::resource('post-categories', \App\Http\Controllers\Admin\PostCategoryController::class);
    Route::post('posts/{post}/blocks', [\App\Http\Controllers\Admin\PostController::class, 'saveBlocks'])->name('posts.save-blocks');
    Route::post('posts/{post}/duplicate', [\App\Http\Controllers\Admin\PostController::class, 'duplicate'])->name('posts.duplicate');
    Route::get('posts-blocks-source', [\App\Http\Controllers\Admin\PostController::class, 'blocksSource'])->name('posts.blocks-source');
    Route::post('posts/{post}/copy-blocks', [\App\Http\Controllers\Admin\PostController::class, 'copyBlocksFrom'])->name('posts.copy-blocks');
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    Route::resource('product-categories', \App\Http\Controllers\Admin\ProductCategoryController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    // Orders Management
    Route::resource('orders', \App\Http\Controllers\Admin\OrdersManagementController::class);

    // service
    Route::resource('service', \App\Http\Controllers\Admin\ServiceController::class);

    // Contact Management
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class)->only(['index', 'destroy']);


    Route::post('payment-receipts', [\App\Http\Controllers\Admin\PaymentReceiptController::class, 'store']);
    Route::put('payment-receipts/{paymentReceipt}', [\App\Http\Controllers\Admin\PaymentReceiptController::class, 'update']);

});

// Admin Media Serve (Public access for posts)
Route::get('media/file/{filename}', [\App\Http\Controllers\Admin\MediaController::class, 'serve'])
    ->name('admin.media.serve')
    ->where('filename', '.*');

// Post Frontend Routes - Catch-all (Must be last)
Route::get('/search', [\App\Http\Controllers\PostController::class, 'search'])
    ->name('search');

Route::get('{slug}', [\App\Http\Controllers\PostController::class, 'show'])
    ->name('posts.show');
