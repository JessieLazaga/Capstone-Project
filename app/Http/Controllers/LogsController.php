<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage users', ['only'=> ['index']]);
        $this->middleware('can:use POS', ['only'=> ['store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = new Logs();
        $logs = $logs->latest()->Paginate(8, ['*'], 'all');
        return view('admin.logs.logs', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $logs = Logs::create([
            'username' => $request->user()->name,
            'activity' => $request->activity,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Logs  $logs
     * @return \Illuminate\Http\Response
     */
    public function show(Logs $logs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Logs  $logs
     * @return \Illuminate\Http\Response
     */
    public function edit(Logs $logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Logs  $logs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Logs $logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Logs  $logs
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logs $logs)
    {
        //
    }
}
