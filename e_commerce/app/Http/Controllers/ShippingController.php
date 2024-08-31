<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        // dd("1");
        $countries = Country::orderBy('name','ASC')->get();
        $shippingCharges = ShippingCharge::select('shipping_charges.*','countries.name')->leftJoin('countries','countries.id','shipping_charges.country_id')->get();
        // dd($shippingCharges);
        $data['countries'] = $countries;
        $data['shippingCharges'] = $shippingCharges;
        return view('admin.shipping.create', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'amount' => 'required'
        ]);

        if($validator->passes()){

            $shippingCharge = new ShippingCharge();
            $shippingCharge->country_id = $request->name;
            $shippingCharge->charges = $request->amount;
            $shippingCharge->save();

            $request->session()->flash('success','Shipping change added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping change added successfully'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id){
        $countries = Country::orderBy('name','ASC')->get();
        $shippingCharge = ShippingCharge::find($id);
        // dd($shippingCharge);
        $data['shippingCharge'] = $shippingCharge;
        $data['countries'] = $countries;
        return view('admin.shipping.edit', $data);
    }

    public function update($id, Request $request){
        $shippingCharge = ShippingCharge::find($id);

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'amount' => 'required'
        ]);

        if($validator->passes()){

            // $shippingCharge = new ShippingCharge();
            $shippingCharge->country_id = $request->name;
            $shippingCharge->charges = $request->amount;
            $shippingCharge->save();

            $request->session()->flash('success','Shipping change updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Shipping change updated successfully'
            ]);


        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function delete($id){
        $shippingCharge = ShippingCharge::find($id);
        $shippingCharge->delete();

        return redirect()->route('shipping.create');
    }
}
