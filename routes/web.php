<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\ImageFilter;
use App\DataTables\PostsDataTable;
use App\DataTables\UsersDataTable;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PostController;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Permission;
use Intervention\Image\ImageManagerStatic;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('user/{id}/edit',function($id){
    return $id;
})->name('user.edit');
Route::get('/dashboard', function (UsersDataTable $dataTable) {
    return $dataTable->render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/posts', function (PostsDataTable $dataTable) {
//     return $dataTable->render('posts');
// })->middleware(['auth', 'verified'])->name('posts');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('image',function(){
    $img = ImageManagerStatic::make('bleach.jpg');
    $img->filter(new ImageFilter(5));
    // $img->save('bleach3.jpg',100);
    return $img->response();

});

Route::get('/posts',function(){
    $posts = Post::all();
    return view('posts.index',compact('posts'));
});

Route::get('shop',[CartController::class,'shop'])->name('shop');
Route::get('cart',[CartController::class,'cart'])->name('cart');
Route::get('add-to-cart/{id}',[CartController::class,'addToCart'])->name('add-to-cart');
Route::get('qty-increment/{rowId}',[CartController::class,'qtyIncrement'])->name('qty-increment');
Route::get('qty-decrement/{rowId}',[CartController::class,'qtyDecrement'])->name('qty-decrement');

Route::get('remove-product/{rowId}',[CartController::class,'removeProduct'])->name('remove-product');
require __DIR__.'/auth.php';

Route::get('create-role',function(){
    // $role = Role::create(['name' => 'publisher']);
    // return $role;

    // $permission = Permission::create(['name' => 'edit articles']);
    // return $permission;

    $user = auth()->user();
    // $user->assignRole('writer');
   // $user->givePermissionTo('edit articles');
  $checkPermissions =  $user->can('delete articles');
    if ($user->can('delete articles')) {
        return 'user have permission';
    }else{
        return 'user dont have permission';
    }
});

Route::get('/auth/redirect',function(){
  return  Socialite::driver('github')->redirect();
})->name('github.login');

Route::get('/auth/callback',function(){
    $user = Socialite::driver('github')->user();
    
    $user = User::firstOrCreate([
        'email'=>$user->email
    ],[
        'name'=>$user->name,
        'password'=> bcrypt(Str::random(24))
    ]);

    Auth::login($user,true);

    return redirect('/dashboard');
});

Route::get('/auth/gredirect',function(){
   return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/gcallback',function(){
    $user = Socialite::driver('google')->user();
 
    $user = User::firstOrCreate([
        'email'=>$user->email
    ],[
        'name'=>$user->name,
        'password'=> bcrypt(Str::random(24))
    ]);

    Auth::login($user,true);

    return redirect('/dashboard');
});