<?php

namespace App\Http\Controllers;

use App\Form;
use App\User;
use App\Year;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function hasPermission($permission){
        $user = auth()->user();
        return $user->can($permission);
    }

    public function all(){
        if (!$this->hasPermission("admin-users-list")){
            return response()->json(['no-permission']);
        }
        $users = User::with(['years','forms'])->get();
        $return = array();

        foreach ($users as $user){
            $userData = array();
            $nameSplit = explode(" ", $user->name);

            $userData['id'] = $user->id;
            $userData['firstName'] = $nameSplit[0];
            if (count($nameSplit) > 1){
                $userData['lastName'] = $nameSplit[1];
            } else {
                $userData['lastName'] = '-';
            }
            $userData['email'] = $user->email;
            $userData['form'] = $user->forms === null ? '-' : $user->forms->form;
            $userData['year'] = $user->years === null ? '-' : $user->years->year;
            $userData['joined'] = date('d/m/y', strtotime($user->created_at));
            $userData['role'] = $user->getRoleNames()[0];
            array_push($return, $userData);

        }
        return $return;
    }

    public function edit(Request $request, $id){
        if (!$this->hasPermission("admin-users-edit")){
            return response()->json(['no-permission']);
        }
        $user = User::findOrFail($id);
        if ($user == null){
            return response()->json(['errors' => 'user-not-found']);
        }

        // This array will be used to send back errors, we can push new errors to this array that the JS can process
        $errors = array();

        // The full name is stored as one string, so we need to split the current one so we can assign each part a new value if it has one.
        $nameSplit = explode(" ", $user->name);
        $oldFirst = $nameSplit[0];
        $oldLast = $nameSplit[1];

        // We try to assign the values given from the POST request, if not present we just assign the original as default.
        $newFirst = $request->input('firstName', $oldFirst);
        $newLast = $request->input('lastName', $oldLast);

        // We then recreate the full name as one string ad assign it back the the user object.


        $name = strip_tags($newFirst . " " . $newLast);
        //echo $name;
        $user->name = $name;

        // If a user has changed the Form or Year we need to make sure it is a valid entry. We also dont want to spend time
        // executing queries if they haven't changed a Form or Year
        $formString = $request->input('form');
        //formString and yearString are fixed inputs from select options, but someone could still try and force in some code
        $formString = strip_tags($formString);
        // If the form given in the post data isn't null or still the same as its original
        if ($formString !== null && $formString !== $user->form){
            $form = Form::where('id', $formString)->first();
            // We try to find the first Form with the given ID. I could use findOrFail with an ID but first is fine here.
            // If null we can add an error and not update
            if ($form === null){
                array_push($errors, 'form-not-found');
            } else {
                $user->form = intval($formString);
            }
        }

        // same as previous
        $yearString = $request->input('year');
        $yearString = strip_tags($yearString);
        if ($yearString !== null && $yearString !== $user->year){
            $year = Year::where('id', $yearString)->first();
            // We try to find the first Year with the given ID. I could use findOrFail with an ID but first is fine here.
            // If null we can add an error and not update
            if ($year === null){
                array_push($errors, 'year-not-found');
            } else {
                $user->year = intval($yearString);
            }
        }

        // Finally we can update the email, or set it back to itself if it hasn't changed
        $email = strip_tags($request->input('email', $user->email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $user->email = $email;
        } else {
            array_push($errors, 'email-invalid');
        }



        // Now we can save the user and return any errors.

        $user->save();
        if (count($errors) === 0){
            array_push($errors, "success");
        }

        // Due to the nature of saving things can be saved even if parts are invalid. So we will need to alert the user
        // that it has saved but anything red hasn't been changed.
        return response()->json($errors);




    }

    public function get($id){
        if (!$this->hasPermission("admin-users-get")){
            return response()->json(['no-permission']);
        }
        $user = User::find($id);

        if ($user === null){
            return response()->json(['no-user']);
        }

        $userData = array();
        $nameSplit = explode(" ", $user->name);

        $userData['id'] = $user->id;
        $userData['firstName'] = $nameSplit[0];
        $userData['lastName'] = count($nameSplit) > 1 ? $nameSplit[1] : "-";
        $userData['email'] = $user->email;
        $userData['form'] = $user->form;
        $userData['year'] = $user->year;
        $userData['joined'] = date('d/m/y', strtotime($user->created_at));
        $role = ($user->roles)[0];
        $userData['role']['name'] = $role['name'];
        $userData['role']['id'] = $role['id'];

        return response()->json($userData);
    }

    public function delete(Request $request){
        if (!$this->hasPermission("admin-users-delete")){
            return response()->json(['no-permission']);
        }
        $user = User::findOrFail($request->input('id'));
        if ($user === null){
            return response()->json(['user-not-found']);
        }

        $user->delete();
        return response()->json(['success']);
    }

    public function roles(){
        $roles = \Spatie\Permission\Models\Role::all();
        $r = array();
        foreach($roles as $role) {
            $rr = array();
            $rr['id'] = $role->id;
            $rr['name'] = $role->name;
            array_push($r, $rr);
        }
        return response()->json($r);
    }


}
