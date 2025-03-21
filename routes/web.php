<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified','role:SuperAdmin'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


   Route::middleware('role:SuperAdmin')->group(function(){
    Volt::route('items','items.index')->name('items.index');
    Volt::route('items/create','items.create')->name('items.create');
    Volt::route('items/{id}/edit','items.edit')->name('items.edit');
    Volt::route('items/{id}/show','items.show')->name('items.show');


    Volt::route('invoices','invoices.index')->name('invoices.index');
    Volt::route('invoices/create','invoices.create')->name('invoices.create');
    Volt::route('invoices/{id}/edit','invoices.edit')->name('invoices.edit');


    Volt::route('users','users.index')->name('users.index');
    Volt::route('users/create','users.create')->name('users.create');
    // Volt::route('users/{id}/edit','users.edit')->name('users.edit');
   });

   Route::middleware('role:ShopOwner')->group(function(){
    Route::view('user-dashboard', 'user-dashboard')
    ->middleware(['auth', 'verified','role:SuperAdmin,ShopOwner'])
    ->name('user-dashboard');

    Volt::route('user-items','user-dashboard.items.index')->name('user-items.index');

   });


});

require __DIR__.'/auth.php';
