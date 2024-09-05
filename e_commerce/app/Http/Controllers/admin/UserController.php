<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::where('role', '!=', 2)->orderby('created_at')->with('getUserAddress')->get();
        // dd($users);
        $data['users'] = $users;
        return view('admin.user.list', $data);
    }

    public function create(){
        $countries = Country::orderBy('name', 'ASC')->get();
        $data['countries'] = $countries;
        return view('admin.user.create', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required',
            'gender' => 'required',
            'status' => 'required',
        ]);
        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->gender = $request->gender;
            $user->status = $request->status;
            $user->password = Hash::make($request->password);
            $user->save();
            $request->session()->flash('success', 'User Created successfully');
            return response()->json([
                'status' => true,
                'message' => 'User Created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id){
        $user = User::where('id', $id)
                    ->where('role', '!=', 2)->first();
        $countries = Country::orderBy('name', 'ASC')->get();
        $userAddress = CustomerAddress::where('user_id', $user->id)->first();
        $data['user'] = $user;
        $data['userAddress'] = $userAddress;
        $data['countries'] = $countries;
        return view('admin.user.edit', $data);
    }

    public function userDetailUpdate($id ,Request $request){
        $user = User::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id.'id',
            'phone' => 'required|unique:users,phone,'.$user->id.'id',
            'status' => 'required',
            'gender' => 'required',
        ]);
        if($validator->passes()){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;
            $user->gender = $request->gender;
            $user->save();
            $request->session()->flash('success', 'User updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'User Updated successfully',
                'id' => $id
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function userAddressDetailUpdate($id ,Request $request){
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
                'message' => 'User Address updated successfully',
                'id' => $id
            ]);
        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function delete($id, Request $request){
        $user = User::find($id);
        if(!isset($user)){
            $request->session()->flash('error', 'User Not found');
            return redirect()->route('user.index');
        }
        $user->delete();
        $request->session()->flash('success', 'User deleted Successfully');
        return redirect()->route('user.index');       
    }
}
