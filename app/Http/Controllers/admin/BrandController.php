<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = Brand::orderBy('id','ASC');

        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->keyword.'%');
        }

        $brands = $brands->paginate(10);
        return view('admin.brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' =>'required'
        ]);
        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success','Brands Created Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brands Created Successfully'
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $brand = Brand::find($id);

        if(empty($brand)){
            $request->session()->flash('error','Records Not Found');
            return redirect()->route('brands.index');
        }
        else
        {
            return view('admin.brands.edit',compact('brand'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if(empty($brand)){
            $request->session()->flash('error','Records Not Found');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' =>'required'
        ]);
        if($validator->passes()){

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success','Brands Updated Successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brands Updated Successfully'
            ]);
        }
        else
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
        $brand = Brand::find($id);
        if(empty($brand)){
         $request->session()->flash('error','Brands Not Found');
         return redirect()->route('brands.index');
        }
         $brand->delete();
         $request->session()->flash('success','Brands Deleted Successfully');
         return redirect()->route('brands.index');
    }
}
