<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

return [

	/*
	|--------------------------------------------------------------------------
	| View Storage Paths
	|--------------------------------------------------------------------------
	|
	| Most templating systems load templates from disk. Here you may specify
	| an array of paths that should be checked for your views. Of course
	| the usual Laravel view path has already been registered for you.
	|
	*/

	'paths' => [
		realpath(base_path('resources/views'))
	],

	/*
	|--------------------------------------------------------------------------
	| Compiled View Path
	|--------------------------------------------------------------------------
	|
	| This option determines where all the compiled Blade templates will be
	| stored for your application. Typically, this is within the storage
	| directory. However, as usual, you are free to change this value.
	|
	*/

	'compiled' => realpath(storage_path().'/framework/views'),

];
