<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
  public function __construct()
  {
      // Adds middleware "auth" to any requests that use this Controller. Prevent un-authorised access and changes
      $this->middleware('auth');


  }

  private function hasAccess($optPerm = null){
    $user = Auth::user();
    if($user === null || !($user->can('admin')) || ($optPerm !== null && $user->can("admin-".$optPerm))){
      abort(403);
    }
    return;

  }

  public function index(){
    $this->hasAccess();
    return view("admin.index");
  }
}
