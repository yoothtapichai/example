<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
class BackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend.dashboard');
    }
    public function user_management()
    {
        // exit();
        $users = User::all()->sortByDesc('created_at');
        // var_dump($all_cars);
        return view('backend.user-management', compact('users'));
    }
    public function addUser(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],


        ]);



        if ($validator->fails()) {

            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $addUser = new User;
                $addUser->name = $request->name;
                $addUser->email = $request->email;
                $addUser->password = Hash::make($request->password);
                $addUser->type = ($request->type == 1) ? $request->type : 0;
                $addUser->created_by     = Auth::user()->id;
                $addUser->updated_by     = Auth::user()->id;
                $addUser->created_at     = date("Y-m-d H:i:s");
                $addUser->updated_at     = date("Y-m-d H:i:s");

                $addUser->save();
                return response()->json(['success' => true, 'msg' => 'User added success']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }
    }
    public function editUser(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],


        ]);

        if ($validator->fails()) {

            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $editUser = User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'type' => ($request->type == 1) ? $request->type : 0,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);

                return response()->json(['success' => true, 'msg' => 'User updated success']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }
    }
    public function deleteUser($id)
    {


        try {

            
            $user =  Auth::user();
          

            if (File::exists($user->avatar)) {
                File::delete($user->avatar);
            }
            
            $delete = User::where('id', $id)->delete();

            return response()->json(['success' => true, 'msg' => 'User deleted success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function statusUser($id)
    {


        try {
            // ดึงข้อมูลผู้ใช้จาก ID ที่ส่งเข้ามา

            $user = User::where('id', $id)
                ->where('type', '!=', 1)
                ->first();

            // ตรวจสอบว่ามีผู้ใช้หรือไม่
            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User not found or is Admin']);
            }

            if ($user->status == 1) {
                $user->status = 0;
              
            } else {
                $user->status = 1;
             
            }

            $user->save();

            return response()->json(['success' => true, 'msg' => 'User status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
