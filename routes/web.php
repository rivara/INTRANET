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
    Route::get('menu/create/record', 'Auth\Desb2bController@actionRecordMenu')->name('recordMenu');
//DESB2
    Route::get('WebAdmLog', 'Auth\Desb2bController@actionWebAdmLog')->name('WebAdmLog');
    Route::get('Index/', 'Auth\Desb2bController@actionIndex')->name('Index');




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
    Route::post('upload', 'Auth\BibliotecaController@upload')->name('upload');
    Route::get('download', 'Auth\BibliotecaController@actionDownload')->name('download');

//deleteFile
    Route::get('delete', 'Auth\BibliotecaController@actionDeleteFile')->name('deleteFile');
//back
    Route::get('back', 'Auth\AgoraController@actionBackAgora')->name('backAgora');


//DESB2-FERRCASH REVISAR
   // Route::get('WebAdmLog', 'Auth\Desb2bFerrcashController@WebAdmLog')->name('WebAdmLog');
   // Route::get('desb2b-Ferrcash/', 'Auth\Desb2bFerrcashController@backb2b')->name('backb2b');





});
