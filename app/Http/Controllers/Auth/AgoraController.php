<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 06/02/2019
 * Time: 13:56
 */

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Session;
use Illuminate\Pagination\LengthAwarePaginator;

class AgoraController
{

    public function actionAddFile()
    {
        return view('agora/carga');
    }

    public function actionBackAgora()
    {
        return view('agora/docu');
    }

}