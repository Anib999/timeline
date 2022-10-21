<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|AddUpdateUser|CEOAdmin|HRAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $ranks = ['1', '2', '3', '4', '5'];
        return view('pages.positionList', compact('ranks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $positions = Position::orderByDesc('rank')->get();
        $ranks = [];
        for ($i=1; $i < 15; $i++) { 
            array_push($ranks, $i);
        }
        // $ranks = ['1', '2', '3', '4', '5',];
        return view('pages.positionCreate', compact('positions', 'ranks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $positionId = $request->get('position');

        if($positionId != null){
            Position::where('id', $positionId)
            ->update([
                'name' => $request->get('name'),
                'details' => $request->get('details'),
                'rank' => $request->get('rank'),
            ]);
        }else{
            Position::create([
                'name' => $request->get('name'),
                'details' => $request->get('details'),
                'rank' => $request->get('rank'),
            ]);
        }
        return redirect()->route('position.create')->with('message', "Position Successfully Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy($position_id) {

        $position = Position::findorFail($position_id);
        $position->delete();
        return redirect()->route('position.create')->with('message', "Position Successfully deleted");
    }

    public function disableEnablePosition(Request $request){
        $position_id = $request->get('positionId');
        $status = $request->get('status');

        $UpdateStatus = Position::whereRaw('id='.$position_id)->update([
            'status' => $status
        ]);

        if($UpdateStatus == 1){
            return response()->json(['statusChange'=>true]);
        }else{
            return response()->json(['statusChange'=>false]);
        }
    }

}
