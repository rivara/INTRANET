<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'revalidate'], function() {
    Route::get('/', function () {
        return view('auth/login');
    });
    Route::post('login', 'Auth\LoginController@verifica');
    Route::get('descarga', 'Auth\LoginController@actionDescarga')->name('descarga');



//////////////////////
///////Logueo/////////
//////////////////////
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('redirect', 'Auth\LoginController@redirect')->name('redirect');
    //Admin password reset routes
    Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('request');
    Route::post('password/reset','Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot');
    Route::post('password/forgot','Auth\ResetPasswordController@reset');
    Route::get('password/back/{token}','Auth\ResetPasswordController@showResetForm')->name('reset');
    Route::post('password/back','Auth\ResetPasswordController@actionModificaPassword')->name('modificaPassword');



////////////////////////////////
/////Administracion usuarios////
////////////////////////////////
    Route::get('management/admin', 'Auth\AdminController@actionAdmin');
    Route::get('management/users', 'Auth\AdminController@actionSearch')->name('SearchUser');
    Route::get('management/users/admin', 'Auth\AdminController@actionCreateUser')->name('createUser');
    Route::get('management/users/update', 'Auth\AdminController@actionGoUpdateUser')->name('goUpdateUser');
//create
    Route::get('management/users/create', 'Auth\AdminController@actionRecordUser')->name('recordUser');
//update
    Route::get('management/update/update', 'Auth\AdminController@actionUpdateUser')->name('updateUser');
    Route::get('management/update/gruposAdd', 'Auth\AdminController@actionGoAddUserGroup')->name('goAddUserGroup');
    Route::get('management/update/gruposAdd/Add', 'Auth\AdminController@actionAddUserGroup')->name('addUserGroup');
    Route::get('management/update/gruposAdd/Delete','Auth\AdminController@actionDeleteUserGroup')->name('deleteUserGroup');
//delete
    Route::get('management/update/delete', 'Auth\AdminController@actionDeleteUser')->name('deleteUser');
//back
    Route::get('back/home', 'Auth\LoginController@backHome')->name('backHome');



//////////////////////////////
/////Administracion grupos////
//////////////////////////////
    Route::get('groups/groups', 'Auth\GroupsController@actionAdminGroups')->name('groups');
    Route::get('groups/createGroup', 'Auth\GroupsController@actionCreateGroup')->name('createGroup');
    Route::get('groups/editGroup', 'Auth\GroupsController@actionGoUpdateGroup')->name('goUpdateGroup');
//create
    Route::post('groups', 'Auth\GroupsController@actionRecordGroup')->name('recordGroup');
//update
    Route::get('groups/update', 'Auth\GroupsController@actionUpdateGroups')->name('updateGroups');
    Route::get('groups/portalAdd', 'Auth\GroupsController@actionGoAddPortalGroup')->name('goAddGrupoPortal');
    Route::get('groups/portalAdd/Add', 'Auth\GroupsController@actionAddPortalGroup')->name('addGrupoPortal');
    Route::get('groups/portalAdd/Delete', 'Auth\GroupsController@actionDeleteGroupPortal')->name('deleteGroupPortal');
//delete
    Route::get('management/groups/delete', 'Auth\GroupsController@actionDeleteGroup')->name('deleteGroup');
//back
    Route::get('back/admin', 'Auth\LoginController@actionBackAdmin')->name('backAdmin');

///////////////////////////////
/////Administracion portales///
///////////////////////////////
    Route::get('portals', 'Auth\PortalsController@admin')->name('goPortals');
    Route::get('portals/create', 'Auth\PortalsController@actionCreatePortal')->name('createPortal');
    Route::get('portals/editPortals', 'Auth\PortalsController@actionGoUpdatePortal')->name('goUpdatePortal');
//create
    Route::get('portals/createGroup/Add', 'Auth\PortalsController@actionRecord')->name('recordPortal');
//update
    Route::get('portals/editPortal/update','Auth\PortalsController@actionUpdatePortal')->name('updatePortal');
//delete
    Route::get('portals/update/portalAdd/Delete', 'Auth\PortalsController@actionDeletePortal')->name('deletePortal');


///////////////////////////////
/////Administracion menus///
///////////////////////////////

    Route::get('menu/', 'Auth\Desb2bController@actionGoMenu')->name('goMenu');
    Route::get('menu/create', 'Auth\Desb2bController@actionCreateMenu')->name('createMenu');
    Route::get('menu/update', 'Auth\Desb2bController@actionUpdateMenu')->name('updateMenu');
    Route::get('menu/update/dropSubmenu', 'Auth\Desb2bController@actionGoDropSubcategoria')->name('goDropSubCategoria');
    Route::get('menu/update/addSubmenu', 'Auth\Desb2bController@actionGoAddSubcategoria')->name('goAddSubCategoria');
    Route::get('menu/categoria', 'Auth\Desb2bController@actionGoCreateCategoria')->name('goCreateCategoria');
    Route::get('menu/subcategoria', 'Auth\Desb2bController@actionGoCreateSubcategoria')->name('goCreateSubcategoria');
    Route::get('menu/drop/categoria', 'Auth\Desb2bController@actiongoDeleteCategoria')->name('goDeleteCategoria');
    Route::get('menu/drop/subcategoria', 'Auth\Desb2bController@actiongoDeleteSubCategoria')->name('goDeleteSubCategoria');
    Route::post('menu/categorias', 'Auth\Desb2bController@actiongoChargeCategoria')->name('chargeCategoria');





    //crea y borra menu y crea subcategoria
    Route::get('menu/create/categoria', 'Auth\Desb2bController@actionSaveCategoria')->name('saveCategoria');
    Route::get('menu/create/subcategoria', 'Auth\Desb2bController@actionSaveSubCategoria')->name('saveSubCategoria');

    Route::get('menu/drop/drop/categoria', 'Auth\Desb2bController@actionDeleteCategoria')->name('deleteCategoria');
    Route::get('menu/drop/drop/subcategoria', 'Auth\Desb2bController@actionDeleteSubCategoria')->name('deleteSubCategoria');


    //borra menu
    Route::get('menu/delete', 'Auth\Desb2bController@actionDeleteMenu')->name('deleteMenu');
    //borra categoria y subcategoria
    Route::get('menu/delete/subcategoria', 'Auth\Desb2bController@actionDeleteCategoria')->name('deleteCategoria');
    Route::get('menu/delete/categoria', 'Auth\Desb2bController@actionDeleteSubCategoria')->name('deleteSubCategoria');



    Route::get('menu/delete/subcategoria', 'Auth\Desb2bController@actionDeleteMenuSubCategoria')->name('deleteMenuSubCategoria');
    //graba menu
    Route::get('menu/create/record', 'Auth\Desb2bController@actionRecordMenu')->name('recordMenu');
    Route::get('menu/add', 'Auth\Desb2bController@actionGoMenuAdd')->name('goMenuAdd');
    //graba categoria
    Route::get('menu/add/categoria', 'Auth\Desb2bController@actionAddCategorias')->name('sddCategorias');


//DESB2
    //otros -- inicial
    Route::get('Ejemplo', 'Auth\Desb2bController@actionEjemplo')->name('Ejemplo');
    Route::get('WebAdmLog', 'Auth\Desb2bController@actionWebAdmLog')->name('WebAdmLog');
    Route::get('Index/', 'Auth\Desb2bController@actionIndex')->name('Index');
    //consultas

    Route::get('WebSociosCart', 'Auth\Desb2bController@actionWebSociosCart')->name('WebSociosCart');
    Route::get('WebProveedorTarifaCabecera', 'Auth\Desb2bController@actionWebProveedorTarifaCabecera')->name('WebProveedorTarifaCabecera');
    Route::get('WebSociosAcum', 'Auth\Desb2bController@actionWebSociosAcum')->name('WebSociosAcum');
    Route::get('WebProveedorRap', 'Auth\Desb2bController@actionWebProveedorRap')->name('WebProveedorRap');
    Route::get('WebRiesgo', 'Auth\Desb2bController@actionWebRiesgo')->name('WebRiesgo');
    Route::get('WebConformidad', 'Auth\Desb2bController@actionWebConformidad')->name('WebConformidad');
    Route::get('WebArticulos', 'Auth\Desb2bController@actionWebArticulos')->name('WebArticulos');
    //************************************* *Art.Comprados ******************************************
    Route::get('WebSociosAven', 'Auth\Desb2bController@actionWebSociosAven')->name('WebSociosAven');
    Route::get('WebArticulosPres', 'Auth\Desb2bController@actionWebArticulosPres')->name('WebArticulosPres');
    Route::get('WebAdmConfUsu', 'Auth\Desb2bController@actionWebAdmConfUsu')->name('WebAdmConfUsu');
    //movimientos
    Route::get('WebClientesCart', 'Auth\Desb2bController@actionWebClientesCart')->name('WebClientesCart');
    Route::get('WebSociosSuc', 'Auth\Desb2bController@actionWebSociosSuc')->name('WebSociosSuc');
    Route::get('WebSociosAven', 'Auth\Desb2bController@actionWebSociosAven')->name('WebSociosAven');
    Route::get('WebSociosCfac', 'Auth\Desb2bController@actionWebSociosCfac')->name('WebSociosCfac');
    Route::get('WebSociosSucs', 'Auth\Desb2bController@actionWebSociosSucs')->name('WebSociosSucs');
    Route::get('WebSociosPagPen', 'Auth\Desb2bController@actionWebSociosPagPen')->name('WebSociosPagPen');
    Route::get('WebSociosCfac2', 'Auth\Desb2bController@actionWebSociosCfac2')->name('WebSociosCfac2');
    // conversion PHP (prndiente)
    Route::get('toExcell', 'Auth\Desb2bController@actionToExcell')->name('ToExcel');
    // generico se indica la tabla
    Route::get('drop', 'Auth\Desb2bController@actionToExcell')->name('drop');
/////////////////////////
//////Rerporting////////
////////////////////////
    Route::get('reporting', 'Auth\ReportingController@actionReportingRedirect')->name('reportingRedirect');
    Route::get('indiceDeRotacion', 'Auth\ReportingController@actionindiceDeRotacion')->name('indiceDeRotacion');
    Route::get('obsoletos', 'Auth\ReportingController@actionObsoletos')->name('obsoletos');
    Route::get('marcaPropia', 'Auth\ReportingController@actionMarcaPropia')->name('marcaPropia');
    Route::get('marcaPropiaPrueba', 'Auth\ReportingController@actionMarcaPropiaPrueba')->name('marcaPropiaPrueba');


/////////////////////////
//////Biblioteca////////
////////////////////////
//addFile  renombrar
    Route::get('add/file', 'Auth\BibliotecaController@actionGoAddFile')->name('goAddFile');
    Route::get('subgroup', 'Auth\BibliotecaController@actionGoAddSubGroup')->name('goAddSubGroup');
    Route::get('subgroup/go_record', 'Auth\BibliotecaController@actionGoSubGroup')->name('goSubGroup');
    Route::get('subgroup/record', 'Auth\BibliotecaController@actionSubGroupRecord')->name('subGroupRecord');
    Route::get('subgroup/delete', 'Auth\BibliotecaController@actionSubGroupDelete')->name('subGroupDelete');
    Route::get('subCarpeta', 'Auth\BibliotecaController@actionGoSubCarpeta')->name('goSubCarpeta');
    Route::get('carpeta', 'Auth\BibliotecaController@actionBackCarpeta')->name('backCarpeta');
//record file
    Route::post('upload', 'Auth\BibliotecaController@actionUpload')->name('upload');
    Route::get('download', 'Auth\BibliotecaController@actionDownload')->name('download');
//deleteFile
    Route::get('delete', 'Auth\BibliotecaController@actionDeleteFile')->name('deleteFile');


//edit
    Route::get('edit', 'Auth\BibliotecaController@actionGoEditFile')->name('goEditFile');
    Route::post('modify', 'Auth\BibliotecaController@actionModify')->name('modify');
    Route::get('modifyDescription', 'Auth\BibliotecaController@actionModifyDescription')->name('modifyDescription');
    Route::get('edit/description', 'Auth\BibliotecaController@actionGoEditDFile')->name('goEditDFile');
    Route::get('edit/file', 'Auth\BibliotecaController@actionGoSubGrupo')->name('goSubGrupo');
    Route::get('back', 'Auth\AgoraController@actionBackAgora')->name('backAgora');

/////////////////////////
//////Salas////////////
////////////////////////
    Route::get('salas/edit', 'Auth\SalasController@actionGoEditSala')->name('goEditSala');
    Route::get('salas/index', 'Auth\SalasController@actionGoIndexSala')->name('goIndexSala');
    Route::get('salas/record', 'Auth\SalasController@actionGoRecordSala')->name('goRecordSala');
    Route::get('salas/record/save', 'Auth\SalasController@actionRecordSala')->name('recordSala');




//DESB2-FERRCASH REVISAR
   // Route::get('WebAdmLog', 'Auth\Desb2bFerrcashController@WebAdmLog')->name('WebAdmLog');
   // Route::get('desb2b-Ferrcash/', 'Auth\Desb2bFerrcashController@backb2b')->name('backb2b');


/*??
    \DB::listen(function($sql) {
        \Log::info($sql->sql);
        \Log::info($sql->bindings);
        \Log::info($sql->time);
    });*/

});
