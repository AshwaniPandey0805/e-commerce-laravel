<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountCouponController extends Controller
{

    public function index(){
        $coupons = DiscountCoupon::orderBy('start_at', "ASC")->get();
        $data['coupons'] = $coupons;
        return view('admin.discount.list', $data);
    }

    public function create(){
        return view('admin.discount.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:discount_coupons',
            'discount_amount' => 'required|numeric',
        ]);

        if($validator->passes()){

            if(!empty($request->start_at)){
                $now = Carbon::now();
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);

                if($start_at->lte($now)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['start_at' => 'should be less the current date and time value']
                    ]);
                }
            }

            if(!empty($request->end_at)){
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);
                $end_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_at);

                if($end_at->lte($start_at)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['end_at' => 'should be greater than start date and time'] 
                    ]);
                }
            }

            $coupon = new DiscountCoupon();
            $coupon->code = $request->code;
            $coupon->name = $request->name;
            $coupon->description = $request->description;
            $coupon->max_uses = $request->max_uses;
            $coupon->max_uses_user = $request->max_uses_user;
            $coupon->type = $request->type;
            $coupon->discount_amount = $request->discount_amount;
            $coupon->min_amount = $request->min_amount;
            $coupon->status = $request->status;
            $coupon->start_at = $start_at;
            $coupon->end_at = $end_at;
            $coupon->save();

            $request->session()->flash('success', 'Coupon added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Coupon added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'check the details'
            ]);
        }
    }

    public function edit($id){
        $coupon = DiscountCoupon::find($id);
        if(empty($coupon)){
            return redirect()->route('coupon.index')->with('error','Coupon not found');
        }

        $data['coupon'] = $coupon;
        return view('admin.discount.edit', $data);
    }

    public function update($id, Request $request){
        // dd("1");
        $coupon = DiscountCoupon::find($id);
        if(empty($coupon)){
            return response()->json([
                'status' => false,
                'message' => 'coupon not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:discount_coupons,code,'.$coupon->id.'id',
            'discount_amount' => 'required|numeric',
        ]);

        if($validator->passes()){

            if(!empty($request->start_at)){
                $now = Carbon::now();
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);

                if($start_at->lte($now)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['start_at' => 'should be less the current date and time value']
                    ]);
                }
            }

            if(!empty($request->end_at)){
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);
                $end_at = Carbon::createFromFormat('Y-m-d H:i:s', $request->end_at);

                if($end_at->lte($start_at)){
                    return response()->json([
                        'status' => false,
                        'errors' => ['end_at' => 'should be greater than start date and time'] 
                    ]);
                }
            }

            // $coupon = new DiscountCoupon();
            $coupon->code = $request->code;
            $coupon->name = $request->name;
            $coupon->description = $request->description;
            $coupon->max_uses = $request->max_uses;
            $coupon->max_uses_user = $request->max_uses_user;
            $coupon->type = $request->type;
            $coupon->discount_amount = $request->discount_amount;
            $coupon->min_amount = $request->min_amount;
            $coupon->status = $request->status;
            $coupon->start_at = $start_at;
            $coupon->end_at = $end_at;
            $coupon->save();

            $request->session()->flash('success', 'Coupon updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Coupon updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'check the details'
            ]);
        }
    }

    public function delete($id, Request $request){
        $coupon = DiscountCoupon::find($id);
        if(empty($coupon)){
            return redirect()->route('coupon.index')->with('error','Coupon not found');
        }
        $coupon->delete();
        $request->session()->flash('success', 'Coupon deleted');
        return redirect()->route('coupon.index');
    }
}
