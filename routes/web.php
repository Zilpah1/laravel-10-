<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profile\AvatarController;
use App\Http\Controllers\TicketController;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
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

    //Fetch all users(DB facade)
    //$users = DB::select("select * from users where email=?", ['andria@gmail.com']);
    //$users = DB::select("select * from users");
    //$users = DB::table('users')->first();
    //$users = User::where('id', 1)->first();
    
    //$user = User::find(13);//dd($users);


    //Create a new user
    //$user = DB::insert('insert into users (name, email, password) values(?, ?, ?)',[
    //'Sarthak',
    //'sarthak@bitfumes.com',
    //'password',
    //]);
    //dd($users);
    //Create new user using DB Query
    //$user = DB::table('users') ->insert([
    //    'name'=>'Sarthak',
    //    'email'=> 'sarthak2@bitfumes.com',
    //    'password'=> 'password',
    //]);
    //$user = User::create([
    //    'name'=>'Sarthak',
    //    'email'=> 'sarthak6@bitfumes.com',
    //    'password'=> 'password',
    //]);


    //Update
    //$user = DB:: update("update users set email = 'abc@bitfumes.com' where id=2");
    //dd($users);
    //$user = DB::table('users')->where('id', 5)->update(['email'=> 'abc@bitfumes.com']);
    //$user = User::find(9);
    //$user->update([
    //    'email'=> 'sarthak@bitfumes.com',
    //]);


    //Delete
    //$user = DB::delete("delete from users where id=2");
    //$user = DB::table('users')->where('id', 8)->delete();
    //$user = User::find(9);
    //$user->delete();
    //dd($user->name);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::patch('profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::post('/profile/avatar/ai', [AvatarController::class, 'generate'])->name('profile.avatar.ai');
});

require __DIR__.'/auth.php';


Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    $user = User::firstOrCreate(['email' => $user->email],[
        'name' => $user->name,
        'password' => 'password',
    ]);
 
    Auth::login($user);
    return redirect('/dashboard');
    // $user->token
});

Route::middleware('auth')->group(function(){

    Route::resource('/ticket', TicketController::class);
    //Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
    //Route::post('/ticket/create', [TicketController::class, 'store'])->name('ticket.store');
});

//Reply Route
Route::post('/tickets/{ticket}/replies', 'ReplyController@store')->name('replies.store');
