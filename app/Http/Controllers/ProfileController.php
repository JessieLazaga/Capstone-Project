<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();
            return redirect()->route('login')->with('success','Password is changed successfully');
        }else{
            return redirect()->back()->with('error','Current Password is invalid');
        }
    }
    public function userProfile(){
        if(Auth::user()){
            $user = User::find(Auth::user()->id);
            if($user){
                return view('admin.body.updateProfile',compact('user'));
            }
        }
    }
    public function profileUpdate(Request $request){
        $user=User::find(Auth::user()->id);
        if($user){
            $user->name = $request['name'];
            $user->email = $request['email'];

            $user->save();
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
