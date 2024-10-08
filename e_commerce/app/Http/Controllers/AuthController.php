<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');
    }

    public function register(){
        return view('front.account.register');
    }

    public function registerProcess(Request $request){
        // dd($request->all()); 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|min:10|max:15',
            'password' => 'required|min:6|confirmed'
        ]);

        if($validator->passes()){

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->role = 1;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User created'
            ]);
            

        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function authentication(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){
            
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
                // return view
                if(session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {
                return redirect()
                    ->route('front.login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Either email/password is in-valid');
            }

        } else {
            return redirect()->route('front.login')
                            ->withErrors($validator)
                            ->withInput($request->only('email'));
        }
    }

    public function profile(){
        $countries = Country::orderBy('name', 'ASC')->get();
        $user = Auth::user();
        $userAddress = CustomerAddress::where('user_id', $user->id)->first();
        $data['user'] = $user;
        $data['userAddress'] = $userAddress;
        $data['countries'] = $countries;
        // dd($user);
        return view('front.account.profile', $data);
    }

    public function updateUserProfile($id ,Request $request){
        // dd($request->all(), $id);
        $user = User::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id.'id',
            'phone' => 'required|unique:users,phone,'.$user->id.'id',
        ]);
        if($validator->passes()){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            $request->session()->flash('success', 'User updated successfully');
            return response()->json([
                'status' => true,
                'errors' => 'User Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateUserAddress($id ,Request $request){
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required',
            'apartement' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'mobile' => 'required',
        ]);

        if($validator->passes()){
            CustomerAddress::updateOrCreate(
                ['user_id' => $id],
                [
                    'user_id' => $id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartement' => $request->apartement,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                ]
            );

            $request->session()->flash('success', 'Address updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'User Address updated successfully'
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function order(){
        $orders = Order::where('user_id', Auth::user()->id )->orderby('created_at', 'DESC')->paginate(10);

        $data['orders'] = $orders;
        return view('front.account.myOrder', $data);
    }

    public function orderDetail($id){
        $order = Order::where('id', $id )->first();
        // dd($order);
        $orderItems = OrderItem::where('order_id', $order->id)->with('products.product_images')->get();
        // dd($orderItems);
        $data['order'] = $order;
        $data['orderItems'] = $orderItems;


        return view('front.account.orderDetail', $data);
    }

    public function logout(){
        Auth()->logout();
        return redirect()->route('front.login')->with('success','logout successfully');
    }
}
