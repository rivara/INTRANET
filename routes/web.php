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

// Página de inicio
Route::group(['middleware' => 'revalidate'], function() {
    Route::get('/', function () {
        return view('auth/login');
    });
    Route::post('login', 'Auth\LoginController@verifica');
    Route::get('descarga', 'Auth\LoginController@actionDescarga')->name('descarga');




//Logueo
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('redirect', 'Auth\LoginController@redirect')->name('redirect');

//Administracion usuarios
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


//Administracion grupos
    Route::get('management/groups/groups', 'Auth\GroupsController@actionAdminGroups')->name('groups');
    Route::get('management/groups/createGroup', 'Auth\GroupsController@actionCreateGroup')->name('createGroup');
    Route::get('management/groups/editGroup', 'Auth\GroupsController@actionGoUpdateGroup')->name('goUpdateGroup');
//create
    Route::post('management/groups', 'Auth\GroupsController@actionRecordGroup')->name('recordGroup');
//update
    Route::get('management/groups/update', 'Auth\GroupsController@actionUpdateGroups')->name('updateGroups');
    Route::get('management/groups/portalAdd', 'Auth\GroupsController@actionGoAddPortalGroup')->name('goAddGrupoPortal');
    Route::get('management/groups/portalAdd/Add', 'Auth\GroupsController@actionAddPortalGroup')->name('addGrupoPortal');
    Route::get('management/groups/portalAdd/Delete',
        'Auth\GroupsController@actionDeleteGroupPortal')->name('deleteGroupPortal');
//delete
    Route::get('management/groups/delete', 'Auth\GroupsController@actionDeleteGroup')->name('deleteGroup');
//back
    Route::get('back/admin', 'Auth\LoginController@actionBackAdmin')->name('backAdmin');


//Administracion portales
    Route::get('management/portals', 'Auth\PortalsController@admin')->name('portals');
    Route::get('management/portals/create', 'Auth\PortalsController@actionCreatePortal')->name('createPortal');
    Route::get('management/portals/editPortals', 'Auth\PortalsController@actionGoUpdatePortal')->name('goUpdatePortal');
//create
    Route::get('management/portals/createGroup/Add', 'Auth\PortalsController@actionRecord')->name('recordPortal');
//update
    Route::get('management/portals/editPortal/update','Auth\PortalsController@actionUpdatePortal')->name('updatePortal');
//delete
    Route::get('management/update/portalAdd/Delete', 'Auth\PortalsController@actionDeletePortal')->name('deletePortal');



//Agora
//addFile
    Route::get('add', 'Auth\AgoraController@upload')->name('upload');
    Route::get('add/file', 'Auth\AgoraController@actionGoAddFile')->name('goAddFile');

//deleteFile
    Route::get('delete', 'Auth\AgoraController@actionDeleteFile')->name('deleteFile');
//back
    Route::get('back', 'Auth\AgoraController@actionBackAgora')->name('backAgora');


//DESB2
    Route::get('desb2b/prueba', 'Auth\Desb2bController@prueba')->name('prueba');
    Route::get('desb2b/', 'Auth\Desb2bController@backb2b')->name('backb2b');

//DESB2-FERRCASH REVISAR
    Route::get('desb2b-Ferrcash/prueba', 'Auth\Desb2bFerrcashController@prueba')->name('prueba');
    Route::get('desb2b-Ferrcash/', 'Auth\Desb2bFerrcashController@backb2b')->name('backb2b');
//Admin password reset routes
    Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('request');
    Route::post('password/reset','Auth\ForgotPasswordController@sendResetLinkEmail')->name('forgot');
    Route::post('password/forgot','Auth\ResetPasswordController@reset');
    Route::get('password/back/{token}','Auth\ResetPasswordController@showResetForm')->name('reset');
    Route::post('password/back','Auth\ResetPasswordController@actionModificaPassword')->name('modificaPassword');

});
