<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Key;
use Validator;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->resSuccess(
            Key::all()->toArray(),
            'Keys retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'vehicle_id'  => 'required|integer',
            'name'        => 'required|unique:keys',
            'description' => 'required',
            'price'       => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->resError('Validation Error.', $validator->errors());
        }

        $key = Key::create($input);

        return $this->resSuccess(
            $key->toArray(),
            'Key created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function show(Key $key)
    {
        if (is_null($key)) {
            return $this->resError('Key not found.');
        }

        return $this->resSuccess(
            $key->toArray(),
            'Key retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Key $key)
    {
        $input = $request->only('vehicle_id', 'name', 'description', 'price');

        $validator = Validator::make($input, [
            'vehicle_id'  => 'integer',
            'name'        => 'unique:keys',
            'description' => 'string',
            'price'       => 'numeric'
        ]);

        if ($validator->fails()) {
            return $this->resError('Validation Error.', $validator->errors());
        }

        $key->fill($input);
        $key->save();

        return $this->resSuccess(
            $key->toArray(),
            'Key updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function destroy(Key $key)
    {
        $key->delete();

        return $this->resSuccess(
            $key->toArray(),
            'Key deleted successfully.'
        );
    }
}
