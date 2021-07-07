<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $sensors = Sensor::all();
        return view('crud.crudSensor')->with('sensors',$sensors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crud.crudCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $sensors = new Sensor();

        $sensors->name = $request->get('name');
        $sensors->location = $request->get('location');
        $sensors->description = $request->get('description');

        $sensors->save();
        
        return redirect('/sensors');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function show(Sensor $sensor)
    {
        $rawdata = Sensor::select('val', 'date')->get();
        return view('charts.historicChart',compact('rawdata'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sensor = Sensor::find($id);
        
        return view('crud.crudEdit')->with('sensor',$sensor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sensors = Sensor::find($id);

        $sensors->name = $request->get('name');
        $sensors->campus = $request->get('campus');
        $sensors->location = $request->get('location');
        $sensors->description = $request->get('description');

        $sensors->save();
        
        return redirect('/sensors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Sensor::find($id)->delete();
      
        return redirect('/sensors');
    }

    public function id($name)
    {
        $rawdata = Sensor::select('val', 'date', 'name')
        ->where('name','=',$name)
        ->get();        
        return view('charts.historicChart',compact('rawdata'));
    }

    public function filter(Request $request, $name)
    {   
        if($request->initialDate > $request->finalDate){
            //Alert::message('this is a test message', 'info');
            return 0;
        }
        else{
            $rawdata = Sensor::select('val', 'date')    
            ->where('date', '>=', $request->initialDate)
            ->where('date', '<=', $request->finalDate)
            ->where('name', '=', $name)
            ->get();
            return view('charts.filter', compact('rawdata'));       
        }         
    }

    public function event($name)
    {           
        $rawdata = Sensor::select('val', 'date')
        ->where('event','=',1)
        ->where('name','=',$name)
        ->get();        
        return view('charts.eventChart',compact('rawdata'));
    }
}
