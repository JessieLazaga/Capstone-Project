<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\procurement;
use Auth;

class ManufacturerController extends Controller
{
    public function index()
    {
        $procurements = procurement::where('manufacturer_id', Auth::user()->id)->where('confirmed', false)->get();
        return view('manufacturer.index')->with('procurements', $procurements);
    }
    public function delivered(){
        $procurement = procurement::find(request('procure_id'));
        $procurement->status = true;
        $procurement->save();
    }
}
