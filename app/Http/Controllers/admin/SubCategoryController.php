<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')->orderBy('sub_categories.id','desc')->leftJoin('categories','categories.id','sub_categories.category_id');
        
        if (!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name','like','%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orwhere('categories.name','like','%'.$request->get('keyword').'%');
        } 
        
        $subCategories = $subCategories->paginate(10);
        //dd($subCategories);
       return view('admin.sub_category.index',compact('subCategories'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.sub_category.create',compact('categories'));
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
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()){
            
             $subCategory = new SubCategory();
             $subCategory->name = $request->name;
             $subCategory->slug = $request->slug;
             $subCategory->status = $request->status;
             $subCategory->category_id = $request->category;
             $subCategory->save();

             $request->session()->flash('success','Sub Category Created Successfully');

             return response([
                'status' => true,
                'message' => 'Sub Category Created Successfully'
             ]);
        }
        else{
             return response([
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
    public function edit($id, Request $request)
    {
        $subCategory = SubCategory::find($id);
        
        if(empty($subCategory)){
            $request->session()->flash('error','Records Not Found');
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.sub_category.edit',compact('categories','subCategory'));
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

           $subCategory = SubCategory::find($id);

           if(empty($subCategory)){
            $request->session()->flash('error','Records Not Found');
              return response([
                 'status' => false,
                 'notFound' => true
              ]);
           }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()){
            
             $subCategory->name = $request->name;
             $subCategory->slug = $request->slug;
             $subCategory->status = $request->status;
             $subCategory->category_id = $request->category;
             $subCategory->save();

             $request->session()->flash('success','Sub Category Updated Successfully');

             return response([
                'status' => true,
                'message' => 'Sub Category Updated Successfully'
             ]);
        }
        else{
             return response([
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
        $subCategory = SubCategory::find($id);
          if(empty($subCategory)){
            $request->session()->flash('error','Sub Category Not Found');
             return redirect()->route('sub-categories.index');
          }
        $subCategory->delete();
        $request->session()->flash('success','Sub Category Deleted Successfully');
        return redirect()->route('sub-categories.index');
    }
}
