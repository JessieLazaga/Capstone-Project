<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function changePass(){
        return view('admin.body.changepassword');
    }
    public function updatePass(Request $request){
        $validateData = $request->validate([
            'oldpassword' => 'required',
            'password' => 'required|confirmed'
        ]);
        $hashedPassword = Auth::user()->password;
        if(Hash::check($request->oldpassword,$hashedPassword)){
            $admin = Admin::find(Auth::id());
            $admin->password = Hash::make($request->password);
            $admin->save();
            Auth::logout();
            return redirect()->route('login')->with('success','Password is changed successfully');
        }else{
            return redirect()->back()->with('error','Current Password is invalid');
        }
    }
    public function userProfile(){
        if(Auth::user()){
            $admin = Admin::find(Auth::user()->id);
            if($admin){
                return view('admin.body.updateProfile',compact('admin'));
            }
        }
    }
    public function profileUpdate(Request $request){
        $admin = Admin::find(Auth::user()->id);
        if($admin){
            $admin->name = $request['name'];
            $admin->email = $request['email'];

            $admin->save();
            return redirect()->back()->with('success','Update Successfully');
        }else{
            return redirect()->back();
        }
    }
    public function Logout(){
        Auth::logout()    ;
        return Redirect()->route('login');
    }
    
}
