<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(){
        $pages = Page::orderBy('name', 'ASC')->paginate(10);
        $data['pages'] = $pages;
        return view('admin.page.list', $data);
    }

    public function create(){
        return view('admin.page.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages',
            'content' => 'required',
            'status' => 'required'
        ]);

        if($validator->passes()){

            $page = new Pages();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->status = $request->status;
            $page->save();

            $request->session()->flash('success', 'Page created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Page created successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        $page = Page::find($id);
        $data['page'] = $page;
        return view('admin.page.edit', $data);
    }

    public function update($id, Request $request){
        $page =  Page::find($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages',
            'content' => 'required',
            'status' => 'required'
        ]);

        if($validator->passes()){
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->status = $request->status;
            $page->save();

            $request->session()->flash('success', 'Page updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Page updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function delete($id, Request $request){
        $page = Page::find($id);
        $page->delete();
        $request->session()->flash('success', 'Page delete successfully');
        return redirect()->route('page.index');
        
    }
}
