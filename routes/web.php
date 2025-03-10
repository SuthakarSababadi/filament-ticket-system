<?php

use App\Models\User;
use App\Livewire\ListTickets;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

  // dd( User::find(1)->hasPermission('category_create'));
    return view('welcome');
});

Route::get('tickets',ListTickets::class)->name('tickets.index');