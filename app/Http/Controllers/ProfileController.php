<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {

        $user =  Auth::user();

        if (empty($user->created_by)) {
            $user->created_by = 0;
        }
      
        if(!file_exists($user->avatar) && ($user->provider == 'email')){
            $user->avatar = '';
        }
        // if()
        return view('backend.Profile', compact('user'));
    }
    public function edit_name_profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],

        ]);

        if ($validator->fails()) {

            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $editUser = User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);

                return response()->json(['success' => true, 'msg' => 'User updated success']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }
    }
    public function edit_pass_profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],

        ]);

        if ($validator->fails()) {

            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $editUser = User::where('id', $request->id)->update([
                    'password' => Hash::make($request->password),
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);

                return response()->json(['success' => true, 'msg' => 'User updated success']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }
    }
    public function edit_detail_profile(Request $request)
    {
        try {

            $user =  Auth::user();


            $users = User::where('id',  $user->created_by)->first();

            $data['name'] = $users->name;
            $data['email'] = $users->email;
            return response()->json(['success' => true, 'msg' => 'User updated success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function delete_user_profile(Request $request)
    {
        try {

            $user =  Auth::user();
          

            if (File::exists($user->avatar)) {
                File::delete($user->avatar);
            }
            $delete = User::where('id', $user->id)->delete();

            return response()->json(['success' => true, 'msg' => 'User deleted success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function delete_img_profile(Request $request)
    {
        try {

            $user = User::find(Auth::user()->id);
          

            if (File::exists($user->avatar)) {
                File::delete($user->avatar);
            }
            $user->avatar = '';
            $user->save();

            return response()->json(['success' => true, 'msg' => 'User deleted success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function edit_img_profile(Request $request)
    {
  
        $validator = Validator::make($request->all(), [
            'uploadFile' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()->toArray()]);
        }
        try {
            if ($request->hasFile('uploadFile')) {
                $image = $request->file('uploadFile');
                $filename = time() . '_' . $image->getClientOriginalName();


                $userlogin =  Auth::user();

                if (File::exists($userlogin->avatar)) {
                    File::delete($userlogin->avatar);
                }

                $storagePath = 'public/profile_images/';
                $image->move($storagePath, $filename);

           
                $user = User::find(Auth::user()->id);
                $user->avatar = $storagePath . $filename;
                $user->updated_by = Auth::user()->id;
                $user->updated_at = date("Y-m-d H:i:s");
                
                $user->save();

                return response()->json(['success' => true, 'msg' => 'Profile image updated successfully']);
            } else {
                return response()->json(['success' => false, 'msg' => 'No image uploaded']);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
