<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Year;

class YearController extends Controller
{
  public function __construct()
  {
      // Adds middleware "auth" to any requests that use this Controller. Prevent un-authorised access and changes
      $this->middleware('auth');
  }


    private function hasPermission($permission){
        $user = auth()->user();
        return $user->can($permission);
    }

  // All required Years will be on the Years page, hidden until required

  // Post request, Will automatically create a new Year after validating inputs
  public function create(Request $request){
    if (!$this->hasPermission("admin-years-new")){
      return response()->json(['no-permission']);
    }
    $year = new Year();
    $year->Year = $request->input('year_name');
    $year->save();
    // Will return "success" (as JSON) if everything ran and was Ok
    return response()->json(['success']);
  }

  // Post request, will try to find a Year with id: $id and then edit it
  public function edit(Request $request, $id){
      if (!$this->hasPermission("admin-years-edit")){
          return response()->json(['no-permission']);
      }
    $year = Year::findOrFail($id);
    if ($year == null){
      return response()->json(['error' => 'year-not-found']);
    }
    $year->Year = $request->input('year_name');
    $year->save();

    // Will return "success" (as JSON) if everything ran and was Ok
    return response()->json(['success']);
  }

  // Delete request, will try to find a Year with id: $id and then delete it
  public function delete(Request $request){
      if (!$this->hasPermission("admin-years-delete")){
          return response()->json(['no-permission']);
      }
    $year = Year::findOrFail($request->input('id'));
    if ($year == null){
      return response()->json(['error' => 'year-not-found']);
    }

    // Deletes model from database and then returns that it was successful
    $year->delete();
    return response()->json(['success']);
  }

  public function get($id){
      if (!$this->hasPermission("admin-years-get")){
          return response()->json(['no-permission']);
      }

      $year = Year::findOrFail($id);

      if ($year === null){
          return response()->json(['year-not-found']);
      }

      $data = array();
      $data['id'] = $year->id;
      $data['year'] = $year->year;

      return response()->json($data);
  }

  public function all(){
      if (!$this->hasPermission("admin-years-list")){
          return response()->json(['no-permission']);
      }
    $years = Year::all();
    $data = array();
    foreach ($years as $year){
      $y = array();
      $y['id'] = $year->id;
      $y['year'] = $year->year;
      $y['created'] = date('d/m/y', strtotime($year->updated_at));
      array_push($data, $y);
    }
    return response()->json($data);
  }
}
