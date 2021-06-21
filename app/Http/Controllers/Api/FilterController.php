<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Filter as FilterResource;

use Auth;
use Cookie;

use App\Filter;

class FilterController extends Controller
{

    private $user = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('api')->check())
                $this->user = Auth::guard('api')->user();
            return $next($request);
        });
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user === null)
            return response()->json(['success' => 'false',
                'status_code' => 7007,
                'message' => "Permission Denied"],
                501);

        // should client send screen or retrieve froom session?

        return FilterResource::collection($this->user->filters->sortBy('name', SORT_STRING | SORT_FLAG_CASE));
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
        if ($this->user === null)
            return response()->json(['success' => 'false',
                'status_code' => 7006,
                'message' => "Permission Denied"],
                501);

        $input = $request->only('name', 'screen', 'ident', 'settings', 'default');

        if ($input['ident'] == 0 && empty($input['name']))
        {
            $list = $this->user->filters()->screen($input['screen'])->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE)->map->only(['ident', 'name', 'default']);

            return response()->json(['success' => 'true',
                    'status_code' => 200,
                    'list' => $list,
                    'message' => "Refresh content"],
                    200);
        }

        // parse the settings value into an array
        if (!empty($input['settings']))
            $input['settings'] = Filter::parseSettings($input['settings']);

        unset($input['ident']);
        $filter = new Filter($input);
        $this->user->filters()->save($filter);

        $list = $this->user->filters()->screen($input['screen'])->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE)->map->only(['ident', 'name', 'default']);

        //Cookie::queue('clingen_preferences',$filter->cookie, 0);
        Filter::setBookmark($request, $input['screen'], $filter);

        return response()->json(['success' => 'true',
                'status_code' => 200,
                'list' => $list,
                'new' => $filter->ident,
                'message' => "Filter created"],
                200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($this->user === null)
            return response()->json(['success' => 'false',
                'status_code' => 7004,
                'message' => "Permission Denied"],
                501);

        if ($id === "0")
            return null;

        $filter = Filter::ident($id)->first();

        if ($filter === null || $filter->user->id != $this->user->id)
            return response()->json(['success' => 'false',
                'status_code' => 7009,
                'message' => "Permission Denied"],
                501);

        //Cookie::queue('clingen_preferences',$filter->cookie, 0);
        Filter::setBookmark($request, $filter->screen, $filter);

        return new FilterResource($filter);
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($this->user === null)
            return response()->json(['success' => 'false',
                'status_code' => 7004,
                'message' => "Permission Denied"],
                501);

        if ($id === "0")
            return $this->store($request);

        $filter = Filter::ident($id)->first();

        if ($filter === null || $filter->user->id != $this->user->id)
            return response()->json(['success' => 'false',
                'status_code' => 7005,
                'message' => "Permission Denied"],
                501);

        $input = $request->only('name', 'screen', 'settings', 'default');

        // parse the settings value into an array
        if (!empty($input['settings']))
            $input['settings'] = Filter::parseSettings($input['settings']);

        // if updating default, undo current default
        if (isset($input['default']) && $input['default'] == 1)
        {
            $old = $this->user->filters()->screen($input['screen'])->default()->first();
            if ($old !== null)
                $old->update(['default' => 0]);
        }

        $filter->update($input);

        $list = $this->user->filters()->screen($input['screen'])->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE)->map->only(['ident', 'name', 'default']);

        return response()->json(['success' => 'true',
                'status_code' => 200,
                'list' => $list,
                'message' => "Filter updated"],
                200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user === null)
            return response()->json(['success' => 'false',
                'status_code' => 7001,
                'message' => "Permission Denied"],
                501);

        $filter = Filter::ident($id)->first();

        if ($filter === null || $filter->user->id != $this->user->id)
            return response()->json(['success' => 'false',
                'status_code' => 7002,
                'message' => "Permission Denied"],
                501);

        $screen = $filter->screen;

        $filter->delete();

        $list = $this->user->filters()->screen($screen)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE)->map->only(['ident', 'name', 'default']);

        return response()->json(['success' => 'true',
                'status_code' => 200,
                'message' => "Filter Deleted",
                'list' => $list],
                200);
    }
}
