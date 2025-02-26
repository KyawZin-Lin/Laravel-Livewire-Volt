<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


    Volt::route('items','items.index')->name('items.index');
    Volt::route('items/create','items.create')->name('items.create');
    Volt::route('items/{id}/edit','items.edit')->name('items.edit');

    Volt::route('invoices','invoices.index')->name('invoices.index');
    Volt::route('invoices/create','invoices.create')->name('invoices.create');
    Volt::route('invoices/{id}/edit','invoices.edit')->name('invoices.edit');



});

require __DIR__.'/auth.php';
