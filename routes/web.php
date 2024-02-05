<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IdeaLikeController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
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

/*feed

/profile

*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' =>'idea/','as'=>'idea.','middleware'=>['auth']],function(){

    Route::post('', [IdeaController::class, 'store'])->name('create')->withoutMiddleware(['auth']);

    Route::get('/{idea}', [IdeaController::class, 'show'])->name('show')->withoutMiddleware(['auth']);

    Route::get('/{idea}/edit', [IdeaController::class, 'edit'])->name('edit');

    Route::put('/{idea}', [IdeaController::class, 'update'])->name('update');

    Route::delete('/{idea}', [IdeaController::class, 'destroy'])->name('destroy');

    Route::post('/{idea}/comments', [CommentController::class, 'store'])->name('comments.store');
});
Route::resource('users',UserController::class)->only('show');

Route::resource('users',UserController::class)->only('edit','update')->middleware('auth');

Route::get('profile',[UserController::class,'profile'])->middleware('auth')->name('profile');

Route::post('users/{user}/follow',[FollowerController::class,'follow'])->middleware('auth')->name('users.follow');

Route::delete('users/{user}/unfollow',[FollowerController::class,'unfollow'])->middleware('auth')->name('users.unfollow');

Route::post('idea/{idea}/like',[IdeaLikeController::class,'like'])->middleware('auth')->name('ideas.like');

Route::post('idea/{idea}/unlike',[IdeaLikeController::class,'unlike'])->middleware('auth')->name('ideas.unlike');

Route::get('/feed', [FeedController::class, '__invoke'])->middleware('auth')->name('feed');

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth','can:admin']);

