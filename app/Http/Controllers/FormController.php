<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Form;

class FormController extends Controller
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

  // All required forms will be on the Forms page, hidden until required

  // Post request, Will automatically create a new Form after validating inputs
  public function create(Request $request){
      if (!$this->hasPermission("admin-forms-new")){
          return response()->json(['no-permission']);
      }
    $validatedData = $request->validate([
        'form_name' => 'required|string|max:10',
    ]);
    $form = new Form();
    $form->form = $request->input('form_name');
    $form->save();
    // Will return "success" (as JSON) if everything ran and was Ok
    return response()->json(['success']);
  }

  // Post request, will try to find Form with id: $id and then edit it
  public function edit(Request $request, $id){
      if (!$this->hasPermission("admin-forms-edit")){
          return response()->json(['no-permission']);
      }
    $form = Form::find($id);
    if ($form == null){
      return response()->json(['error' => 'form-not-found']);
    }
    $form->form = strip_tags($request->input('form_name'));
    $form->save();

    // Will return "success" (as JSON) if everything ran and was Ok
    return response()->json(['success']);
  }

  // Delete request, will try to find Form with id: $id and then delete it
  public function delete(Request $request){
      if (!$this->hasPermission("admin-forms-delete")){
          return response()->json(['no-permission']);
      }
    $form = Form::findOrFail($request->input('id'));
    if ($form == null){
      return response()->json(['error' => 'form-not-found']);
    }

    // Deletes model from database and then returns that it was successful
    $form->delete();
    return response()->json(['success']);
  }

  public function get($id){
      if (!$this->hasPermission("admin-forms-get")){
          return response()->json(['no-permission']);
      }

      $form = Form::find($id);

      if ($form === null){
          return response()->json(['form-not-found']);
      }

      $data = array();
      $data['id'] = $form->id;
      $data['form'] = $form->form;

      return response()->json($data);
    }

  public function all(){
      if (!$this->hasPermission("admin-forms-list")){
          return response()->json(['no-permission']);
      }
    $forms = Form::all();
    $data = array();
    foreach ($forms as $form){
      $f = array();
      $f['id'] = $form->id;
      $f['form'] = $form->form;
      $f['created'] = date('d/m/y', strtotime($form->updated_at));
      $f['name'] = '-';
      array_push($data, $f);
    }
    return response()->json($data);
  }

}
