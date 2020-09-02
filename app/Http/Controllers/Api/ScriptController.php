<?php

namespace App\Http\Controllers\Api;

use App\Models\Script;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScriptController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       return Script::orderBy('position', 'asc')->where('site_id', $request->site_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $script = Script::create($request->all());
        return $script;
    }

    /**
     * Position script.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function position(Request $request)
    {
       $idScripts = explode(",", $request->newPosition);
       foreach ($idScripts as $k => $idScript) {
           $gupdateScript = Script::find($idScript);
           $gupdateScript->position = $k;
           $gupdateScript->save();
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Script::findOrFail($request->id)->delete();
    }
}
