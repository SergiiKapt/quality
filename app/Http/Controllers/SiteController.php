<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Http\Requests\Site\CreateSiteRequest;
use App\Http\Requests\Site\UpdateSiteRequest;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site.index')->with([
            'sites' => Site::orderBy('created_at', 'desc')->where('user_id', \Auth::user()->id)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('site.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSiteRequest $request)
    {
        //
        return \DB::transaction(function () use ($request) {
            $site = Site::updateOrCreate([
                'user_id' => \Auth::user()->id,
                'url' => $request->url,
                'name' => $request->name
            ]);

            if ($site) {

                return redirect()->route('sites.index')->with('success', 'Site "'.$site->name. '" was created!');
            }

            throw new GeneralException(__('Profile not created'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //
        return view('site.show')->with([
            'site' => $site,
            'scripts' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('site.edit')->with([
            'site' => Site::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSiteRequest $request, Site $site)
    {
        //

        return \DB::transaction(function () use ($request, $site) {
            if ($site->update([
                'url' => $request->url,
                'name' => $request->name
            ])) {
                return redirect()->route('sites.index')->with('success', 'Site "'.$site->name. '" was updated!');

            }

            throw new GeneralException(__('Profile update error'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        Site::findOrFail($id)->delete();
        return redirect()->route('sites.index')->with('success', __('Site was deleted'));
    }
}
