<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HolidayType;
use App\HolidayYear;
use App\HolidayApplicable;

class HolidayController extends Controller {
  public function __construct() {
        $this->middleware('auth');
        $this->middleware(['roles:SuperAdmin|CEOAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $holidayTypes = HolidayType::get();
        $holidayYears = HolidayYear::get();
        $holidayApplicables = HolidayApplicable::with('holidayyear')->with('holidaytype')->orderBy('holidayDay', 'desc')->get();

        return view('pages.holidayIndex', compact('holidayTypes', 'holidayYears', 'holidayApplicables'));
    }

    /**
     * create Holiday Type
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {


        $request->validate([
            'type_name' => 'required',
            'description' => 'required'
        ]);

        $HolidayTypeStore = HolidayType::create([
                    'type_name' => $request->get('type_name'),
                    'description' => $request->get('description')
        ]);

        if (!$HolidayTypeStore) {
             return redirect()->back()->with('message', 'There was some error , Try Again');
        }
        return redirect()->back()->with('message', 'Successfully Created Holiday Type');
    }

    /*
     * Create Holiday Year
     */

    public function createHoliday(Request $request) {
        $request->validate([
            'year' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);

        $holidayYear = HolidayYear::create([
                'year' => $request->get('year'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
        ]);

        if (!$holidayYear) {
          return redirect()->back()->with('message', 'There was some error , Try Again');
        }
        return redirect()->back()->with('message', 'Successfully Created Year');
    }

    /* Holoiday make appicable  */

    public function makeHolidayApplicable(Request $request) {


        $request->validate([
            'holidayYear' => 'required',
            'holidayType' => 'required',
            'holidayDay' => 'required'
        ]);



        $holidayApplicable = new HolidayApplicable;
        $requestDay = $request->get('holidayDay');
         
       $already_exitsts = $holidayApplicable->where('holidayDay',$requestDay)->pluck('holidayDay')->count();
     
        if ($already_exitsts <= 0) {

            $holidayApplicableSuccess = $holidayApplicable->create([
                'holidayYear' => $request->get('holidayYear'),
                'holidayType' => $request->get('holidayType'),
                'holidayDay' => $requestDay
            ]);

            if (!$holidayApplicableSuccess) {
                return redirect()->back()->with('message', 'There was some error , Try Again');              
            }
        }else{
             return redirect()->back()->with('message', 'Already created this Holiday');
        }

        return redirect()->back()->with('message', 'New Holiday Created');
    }

    public function deleteHoliday_day(Request $request){
        $holiday_dayID = $request->get('holiday_dayID');

        $deleteState = HolidayApplicable::whereRaw('id='.$holiday_dayID)->delete();

        //$deleteState = 1;
        if($deleteState == 1){
            return response()->json(['delete'=>true]);
        }else{
            return response()->json(['delete'=>false]);
        }
    }

    public function removeHolidayYear(Request $request) {
        $holiday_yearID = $request->get('holidayId');
        $deleteState = HolidayYear::whereRaw('id='.$holiday_yearID)->delete();

        if($deleteState == 1){
            return response()->json(['delete'=>true]);
        }else{
            return response()->json(['delete'=>false]);
        }
    }

    public function removeHolidayType(Request $request){
        $holidayTypeId = $request->get('holidayTypeId');
        $deleteState = HolidayType::whereRaw('id='.$holidayTypeId)->delete();
        if($deleteState == 1){
            return response()->json(['delete'=>true]);
        }else{
            return response()->json(['delete'=>false]);
        }
    }

}
