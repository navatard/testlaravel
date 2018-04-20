<?php

Route::get('/', 'MemberController@index')->middleware('web');
Route::post('/lists/create', 'MemberController@create')->middleware('web');
Route::post('/lists/update', 'MemberController@update')->middleware('web');
Route::get('/lists/delete/{id}', 'MemberController@delete')->middleware('web');
