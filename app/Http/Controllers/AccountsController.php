<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function RegView(){
        return view('admin.accounts.usermanage');
    }
}
