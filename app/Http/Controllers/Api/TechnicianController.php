<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Validator;
use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->resSuccess(
            Technician::all()->toArray(),
            'Technicians retrieved successfully.'
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
            'first_name'   => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'last_name'    => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'truck_number' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->resError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $technician = Technician::create($input);

        return $this->resSuccess(
            $technician->toArray(),
            'Technician created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function show(Technician $technician)
    {
        if (is_null($technician)) {
            return $this->resError('Technician not found.');
        }

        return $this->resSuccess(
            $technician->toArray(),
            'Technician retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Technician $technician)
    {
        $input = $request->only('first_name', 'last_name', 'truck_number');

        $validator = Validator::make($input, [
            'first_name'   => 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'last_name'    => 'regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'truck_number' => 'string'
        ]);

        if ($validator->fails()) {
            return $this->resError('Validation Error.', $validator->errors());
        }
        $technician->fill($input);
        $technician->save();

        return $this->resSuccess(
            $technician->toArray(),
            'Technician updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();

        return $this->resSuccess(
            $technician->toArray(),
            'Technician deleted successfully.'
        );
    }
}
