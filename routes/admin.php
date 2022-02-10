<?php

Auth::routes();
Route::get('', function () {
  return redirect('admin/clientes');
});
/* Admin routes */
Route::get('/unauthorized', 'AdminController@unauthorized');
Route::post('/changeActiveYear', 'AdminController@changeActiveYear')->name('years.change');

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {

  /* Clientes */
  Route::get('/clientes/generar-cobro/{rate}', 'CustomerController@clienteRateCharge');
  Route::get('/clientes/{month?}', 'CustomerController@index');
  Route::post('/cliente/update', 'CustomerController@update');
  Route::post('/cliente/setValora', 'CustomerController@setValora');
  Route::post('/cliente/autosaveValora', 'CustomerController@autosaveValora');
  Route::get('/get-mail/{id?}', 'CustomerController@getMail');
  Route::get('/get-rates/{id?}', 'CustomerController@getRates');
  Route::get('/clientes-export', 'CustomerController@exportClients');
  Route::get('/clientes-unassigned/{idUserRate}', 'RatesController@unassignedRate');
  Route::post('/add-subscr', 'CustomerController@addSubscr');
  Route::post('/change-subscr-price', 'CustomerController@changeSubscr');
  Route::get('/cliente-unsubscr/{uID}/{id}', 'CustomerController@rmSubscr');
  Route::get('/cliente/informe/{id}', 'CustomerController@informe');
  Route::post('/cliente/notes', 'CustomerController@addNotes');
  Route::post('/cliente/del-note', 'CustomerController@delNotes');
  Route::post('/cliente/sign', 'CustomerController@addSign');
  Route::get('/cliente/sign/{file?}', 'CustomerController@getSign');
  Route::post('/cliente/enviar-contrato', 'CustomerController@sendConsent');
  Route::get('/cliente/ver_contrato/{id}/{type}', 'CustomerController@seeConsent');
  Route::get('/cliente/downl-consent/{id}/{type}', 'CustomerController@downlConsent');
  Route::get('/cliente/informe/{id}/{tab?}', 'CustomerController@informe');
  Route::get('/cliente/disable/{id}', 'CustomerController@disable');
  Route::get('/cliente/activate/{id}', 'CustomerController@activate');
  Route::get('/cliente/nuevo', 'CustomerController@newCustomer');
  Route::post('/cliente/nuevo', 'CustomerController@saveCustomer');
  Route::get('/see-contrato/{id}/{type}', 'CustomerController@seeContracts');
  Route::post('/clientes/remove-contrato', 'CustomerController@rmContracts');

  /* Citas */
  Route::post('/citas/checkDisp', 'CitasController@checkDateDisp');
  Route::post('/citas/toggleIcon', 'CitasController@toggleIcon');
  Route::post('/citas/chargeAdvanced', 'CitasController@chargeAdvanced');
  Route::get('/citas/duplicar/{id}', 'CitasController@cloneDates');
  Route::post('/citas/duplicar/{id}', 'CitasController@cloneDatesSave');
  Route::get('/citas/delete/{id}', 'CitasController@delete');
  Route::get('/citas/bloqueo-horarios/{type}', 'CitasController@blockDates');
  Route::post('/citas/bloqueo-horarios', 'CitasController@blockDatesSave');
  Route::get('/citas/{type?}/create/{date?}/{time?}', 'CitasController@create');
  Route::post('/citas/create', 'CitasController@save');
  Route::get('/citas/{type?}/informe-nutricion/{id}', 'CitasController@informe');
  Route::get('/citas/{type?}/edit/{id}', 'CitasController@edit');
  Route::get('/citas/{type?}/{month?}/{coach?}/{rate?}', 'CitasController@index');
  Route::get('/citas-listado/{type?}/{coach?}/{rate?}', 'CitasController@listado');
  Route::get('/citas-week/{type?}/{week?}/{coach?}/{rate?}', 'CitasController@indexWeek');
  Route::get('/ver-encuesta/{token}/{control}', 'CustomerController@seeEncuestaNutri');
  Route::post('/clearEncuesta', 'CustomerController@clearEncuestaNutri');
  Route::post('/sendEncuesta', 'CustomerController@sendEncuestaNutri');


  //Facturas
  Route::get('/facturas/ver/{id}', 'InvoicesController@view')->name('invoice.view');
  Route::get('/facturas/nueva/{id}', 'InvoicesController@create')->name('invoice.create');
  Route::get('/facturas/editar/{id}', 'InvoicesController@update')->name('invoice.edit');
  Route::post('/facturas/guardar', 'InvoicesController@save')->name('invoice.save');
  Route::post('/facturas/enviar', 'InvoicesController@sendMail')->name('invoice.sendmail');
  Route::get('/facturas/modal/editar/{charge}/{id?}', 'InvoicesController@update_modal')->name('invoice.updModal');
  Route::post('/facturas/modal/guardar', 'InvoicesController@save_modal');
  Route::delete('/facturas/borrar', 'InvoicesController@delete')->name('invoice.delete');
  Route::get('/facturas/descargar/{id}', 'InvoicesController@download')->name('invoice.downl');
  Route::get('/facturas/descargar-todas', 'InvoicesController@downloadAll');
  Route::get('/facturas/solicitudes/{year?}', 'InvoicesController@solicitudes');
  Route::get('/facturas/{order?}', 'InvoicesController@index');

  /* Cobros */
  Route::get('/generar/cobro', 'ChargesController@generarCobro');
  Route::get('/update/cobro/{id}', 'ChargesController@updateCobro');
  Route::post('/send/cobro-mail', 'ChargesController@sendCobroMail');
  Route::post('/send/cobro-bono', 'ChargesController@sendCobroBono');
  Route::post('/send/cobro-gral', 'ChargesController@sendCobroGral');
  Route::post('/cobros/cobrar', 'ChargesController@cobrar');
  Route::post('/cobros/cobrar/{id}', 'ChargesController@updateCharge');
  Route::get('/cobros/getPriceTax', 'ChargesController@getPriceTax');
  Route::post('/cobros/cobrar-cliente', 'ChargesController@chargeCustomer');
  Route::get('/cliente/cobrar/tarifa/', 'CustomerController@rateCharge');
  
    /* Tarifas */
  Route::get('/tarifas/listado', 'RatesController@index');
  Route::get('/tarifas/new', 'RatesController@newRate');
  Route::post('/tarifas/create', 'RatesController@create');
  Route::get('/tarifas/actualizar/{id}', 'RatesController@actualizar');
  Route::get('/tarifas/update', 'RatesController@update');
  Route::post('/tarifas/upd_fidelity', 'RatesController@upd_fidelity');
  Route::get('/tarifas/delete/{id}', 'RatesController@delete');
  Route::get('/tarifas/stripe/{id}', 'RatesController@createStripe');
  Route::get('/rates/unassigned/{idUserRate}', 'RatesController@unassignedRate');

  /* Bonos */
  Route::get('/bonos/listado', 'BonosController@index');
  Route::post('/bonos/create', 'BonosController@create');
  Route::get('/bonos/update', 'BonosController@update');
  Route::post('/bonos/upd_fidelity', 'BonosController@upd_fidelity');
  Route::get('/bonos/delete/{id}', 'BonosController@delete');
  Route::get('/bonos/sharedBono/{id}/{serv_id}', 'BonosController@sharedBono');
  Route::get('/bonos/sharedBono-get/{id}/{serv_id}', 'BonosController@sharedBono_getlst');
  Route::get('/bonos/comprar/{uid}/{type?}/{id?}', 'BonosController@show_purcharse');
  Route::post('/bonos/comprar', 'BonosController@purcharse');
  Route::post('/bonos/sharedBono', 'BonosController@sharedBono_save');
  Route::get('/bonologs/{id}', 'BonosController@printBonologs');
  Route::post('/bonos/updCant', 'BonosController@updBonologs');
  Route::get('/bonos-clientes', 'BonosController@getByUsers');

  /* informes */
  Route::get('/informes/cajas', 'InformesController@informeCaja');
  
  /* Entrenadores */
    Route::get('/horariosEntrenador/{id?}', 'UsersController@horarios');
  Route::post('/horariosEntrenador', 'UsersController@updHorarios');

});

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {

  /* Ingresos  rutas basicas */
  Route::get('/nuevo/ingreso', 'IncomesController@nuevo');
  Route::post('/ingresos/create', 'IncomesController@create');
  Route::get('/ingresos/{date?}', 'IncomesController@index');
  Route::get('/ingreso-by-rate/{rateID}', 'IncomesController@byRate');
  Route::get('/ingresos', 'IncomesController@index');

  /* Gastos  rutas basicas */
  Route::post('/gastos/create', 'ExpensesController@create');
  Route::post('/gastos/importar', 'ExpensesController@gastos_import');
  Route::post('/gastos/gastosLst', 'ExpensesController@getTableGastos');
  Route::post('/gastos/update', 'ExpensesController@updateGasto');
  Route::get('/gastos/getHojaGastosByRoom/{year?}/{id}', 'ExpensesController@getHojaGastosByRoom');
  Route::get('/gastos/containerTableExpensesByRoom/{year?}/{id}', 'ExpensesController@getTableExpensesByRoom');
  Route::post('/gastos/del', 'ExpensesController@gastosDel');
  Route::get('/gastos-by-byType/{typeID}', 'ExpensesController@byType');
  Route::get('/gastos/{year?}', 'ExpensesController@gastos');

  Route::get('/perdidas-ganancias', 'PyGController@index');

  /* Gastos */
  Route::get('/gastos', 'ExpensesController@index');
  Route::post('/gastos/import/csv/', 'ExpensesController@importCsv');

  /* informes */
  Route::get('/informes/caja/{month?}/{day?}', 'InformesController@informeCajaMes');
  Route::get('/informes/cuotas-mes/{month?}/{day?}', 'InformesController@informeCuotaMes');
  Route::get('/informes/cobros-mes/{month?}/{day?}', 'InformesController@informeCobrosMes');
  Route::get('/informes/cliente-mes/{month?}/{day?}/{f_rate?}/{f_method?}/{f_coach?}', 'InformesController@informeClienteMes');
  Route::post('/informes/search/{month?}', 'InformesController@searchClientInform');
  Route::get('/informes/cierre-diario/{month?}/{day?}', 'InformesController@informeCierreDiario');

  /* Entrenadores */
  Route::get('/sendEmail/trainer/{id}', 'UsersController@sendEmailEntrenadores');
  Route::get('/actualizarEntrenador/{id}', 'UsersController@updEntrenador');
  Route::get('/paymentsEntrenador/{id}', 'CoachLiquidationController@paymentsEntrenador');
  Route::get('/liquidacion-Entrenador/{id}/{date?}', 'CoachLiquidationController@liquidEntrenador');
  Route::get('/enviar-liquidacion-Entrenador/{id}/{date?}', 'CoachLiquidationController@enviarEmailLiquidacion');
  Route::post('/payment-Entrenador/', 'CoachLiquidationController@store');
  Route::get('/entrenadores/liquidacion-entrenador/', 'CoachLiquidationController@coachLiquidation');
  Route::get('/entrenadores/{type?}', 'UsersController@entrenadores');

  /* Usuarios */
//  Route::get('/usuarios', 'UsersController@index');
//  Route::get('/usuarios/new/{role?}', 'UsersController@nueva');
//  Route::post('/usuarios/create', 'UsersController@create');
//  Route::get('/usuarios/actualizar/{id}', 'UsersController@actualizar');
//  Route::any('/usuarios/delete/{id}', 'UsersController@delete');
//  Route::get('/usuarios/actualizarUsuario/{id}', 'UsersController@actualizarUsuario');
//  Route::post('/usuarios/update', 'UsersController@update');
//  Route::post('usuarios/newInforme', 'UsersController@newInforme');

  /* Facturacion */
  Route::get('/facturacion/entrenadores', 'UsersController@entrenadores');
  
  
  Route::get('/manual/bonos', 'ManualController@bonos');
  Route::get('/manual/citas', 'ManualController@citas');
  
  /* Text emails */
  Route::get('/settings_msgs/{key?}', 'SettingsController@messages')->name('settings.msgs');
  Route::post('/settings_msgs/{key?}', 'SettingsController@messages_upd')->name('settings.msgs.upd');
  
});


Route::group(['middleware' => 'auth'], function () {
  Route::get('/importarRegistro', 'FunctionalControler@importarRegistro');
});
  
Route::group(['middleware' => 'superAdmin'], function () {
  Route::get('/control-contabilidad', 'ControlsControler@contabilidad');
  
});