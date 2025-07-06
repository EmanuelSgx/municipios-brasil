<?php

use App\Http\Controllers\MunicipiosController;

Route::get('/ufs/{uf}/municipios', [MunicipiosController::class, 'index']);