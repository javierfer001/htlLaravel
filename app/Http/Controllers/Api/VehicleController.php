<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->resSuccess(
            Vehicle::all()->toArray(),
            'Vehicles retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'year'  => 'required|integer',
            'make'  => 'required',
            'model' => 'required',
            'vin'   => 'required|unique:vehicles|size:17'
        ]);

        if ($validator->fails()) {
            return $this->resError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $vehicle = Vehicle::create($input);

        return $this->resSuccess(
            $vehicle->toArray(),
            'Vehicle created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        if (is_null($vehicle)) {
            return $this->resError('Vehicle not found.');
        }

        return $this->resSuccess(
            $vehicle->toArray(),
            'Vehicle retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'year'  => 'required|integer',
            'make'  => 'required',
            'model' => 'required',
            'vin'   => [
                'required','size:17',
                Rule::unique('vehicles')->ignore($vehicle->id)
            ],
        ]);

        if ($validator->fails()) {
            return $this->resError('Validation Error.', $validator->errors());
        }

        $vehicle->year = $input['year'];
        $vehicle->make = $input['make'];
        $vehicle->model = $input['model'];
        $vehicle->vin = $input['vin'];
        $vehicle->save();

        return $this->resSuccess(
            $vehicle->toArray(),
            'Vehicle updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return $this->resSuccess(
            $vehicle->toArray(),
            'Vehicle deleted successfully.'
        );
    }
}
