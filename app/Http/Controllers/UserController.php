<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VerifyManufacturer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Jetstream;
use App\Http\Requests\UserRegRequest;
use App\Http\Requests\ManufacturerReqRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Mail\VerManufacturer;
use Carbon\Carbon;

class UserController extends Controller
{
    public function store(UserRegRequest $input)
    {
        $user = User::create([
            'name' => $input['name'],
            'password' => Hash::make($input['password']),
        ])->assignRole('user');
        if (! $user){
            return redirect()->back()->with('error', 'Sorry, there was a problem while creating the account.');
        }
        return redirect()->route('regist')->with('success', 'Registered Successfully');
    }
    public function create()
    {
        return view('auth.register');
    }
    public function createMnf()
    {
        return view('auth.registerMnf');
    }
    public function storeMnf(ManufacturerReqRequest $input)
    {
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        $user->assignRole('Manufacturer');
        $user->save();
        if (! $user){
            return redirect()->back()->with('error', 'Sorry, there was a problem while creating the account.');
        }
        VerifyManufacturer::create([
            'user_id' => $user->id,
            'token' => Str::random(60),
        ]);
        Mail::to($user->email)->send(new VerManufacturer($user));
        return redirect()->route('roles.index')->with('success', 'Registered Successfully');
    }
    public function verifyUser($token){
        $user = VerifyManufacturer::where('token', $token)->first();
        if ($user){
            $verifiedUser = $user->user;
            if(!$user->email_verified_at){
                $verifiedUser->email_verified_at = Carbon::now();
                $verifiedUser->save();
                echo "<script>window.close();</script>";
            }else{
                echo "<script>window.close();</script>";
            }
            echo "<script>window.close();</script>";
            }
            else
            {
                echo "<script>window.close();</script>";
            }
        
    }
}
