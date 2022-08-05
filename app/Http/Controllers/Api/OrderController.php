<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->resSuccess(
            Order::with('vehicle')
                ->with('key')
                ->with('technician')
                ->get()
                ->toArray(),
            'Orders retrieved successfully.'
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
            'vehicle_id'    => ['required', Rule::exists('vehicles', 'id')],
            'key_id'        => ['required', Rule::exists('keys', 'id')],
            'technician_id' => ['required', Rule::exists('technicians', 'id')],
            'status'        => ['required',Rule::in(Order::STATUSES)]
        ]);

        if ($validator->fails()) {
            return $this->resError(
                'Validation Error.',
                $validator->errors(),
                422
            );
        }

        $order = Order::create($input);

        return $this->resSuccess(
            $order->toArray(),
            'Order created successfully.'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if (is_null($order)) {
            return $this->resError('Order not found.');
        }

        return $this->resSuccess(
            $order->toArray(),
            'Order retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $input = $request->only('vehicle_id', 'key_id', 'technician_id', 'status');

        $validator = Validator::make($input, [
            'vehicle_id'    => [Rule::exists('vehicles', 'id')],
            'key_id'        => [Rule::exists('keys', 'id')],
            'technician_id' => [Rule::exists('technicians', 'id')],
            'status'        => [Rule::in(Order::STATUSES)]
        ]);

        if ($validator->fails()) {
            return $this->resError('Validation Error.', $validator->errors());
        }
        $order->fill($input);
        $order->save();

        return $this->resSuccess(
            $order->toArray(),
            'Order updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return $this->resSuccess(
            $order->toArray(),
            'Order deleted successfully.'
        );
    }
}
