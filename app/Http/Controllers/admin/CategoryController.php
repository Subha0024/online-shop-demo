<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\TempImage;
use Image;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('id','desc');
        
        if (!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        } 
        
        $categories = $categories->paginate(10);
        //dd($categories);
       return view('admin.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
              'slug' => 'required|unique:categories'
        ]);

        if ($validator->passes()){
           
             $category = new Category();
             $category->name = $request->name;
             $category->slug = $request->slug;
             $category->status = $request->status;
             $category->save();

             /// Save Image Here

             if (!empty($request->image_id)){
                 $tempImage = TempImage::find($request->image_id);
                 $extArray = explode('.',$tempImage->name);
                 $ext = last($extArray);

                 $newImageName = $category->id.'.'.$ext;

                 $sPath = public_path().'/temp/'.$tempImage->name;
                 $dPath = public_path().'/uploads/category/'.$newImageName;
                 File::copy($sPath,$dPath);


                 //Generate Image Thumbnail
                 $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                 $img = Image::make($sPath);
                // $img->resize(450, 600);
                 $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                    });
                 $img->save($dPath);

                 $category->image = $newImageName;
                 $category->save();
             }

             $request->session()->flash('success','Category added Successfully');

             return response()->json([
                'status' => true,
                'message' => 'Category added Successfully'    
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
    public function edit($categoryId, Request $request)
    {
        //echo $categoryId;
        $category = Category::find($categoryId);
        
        if (empty($category)){
            $request->session()->flash('error','Records Not Found');
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($categoryId,Request $request)
    {

        $category = Category::find($categoryId);

        if (empty($category)){
            $request->session()->flash('error','Category Not Found');
           return response()->json([
            'status' => false,
            'notFound' => true,
            'message' => 'Category Not found'
           ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id'
      ]);

      if ($validator->passes()){
         
           $category->name = $request->name;
           $category->slug = $request->slug;
           $category->status = $request->status;
           $category->save();

           $oldImage = $category->image;

           /// Save Image Here

           if (!empty($request->image_id)){
               $tempImage = TempImage::find($request->image_id);
               $extArray = explode('.',$tempImage->name);
               $ext = last($extArray);

               $newImageName = $category->id.'-'.time().'.'.$ext;

               $sPath = public_path().'/temp/'.$tempImage->name;
               $dPath = public_path().'/uploads/category/'.$newImageName;
               File::copy($sPath,$dPath);


               //Generate Image Thumbnail
               $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
               $img = Image::make($sPath);
               //$img->resize(450, 600);
               $img->fit(450, 600, function ($constraint) {
                $constraint->upsize();
                });
               $img->save($dPath);

               $category->image = $newImageName;
               $category->save();

               // Delete Old Images Here
               File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
               File::delete(public_path().'/uploads/category/'.$oldImage);
           }

           $request->session()->flash('success','Category Updated Successfully');

           return response()->json([
              'status' => true,
              'message' => 'Category Updated Successfully'    
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
    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
          if (empty($category)){
            $request->session()->flash('error','Category Not Found');
            return redirect()->route('categories.index');
          }
          File::delete(public_path().'/uploads/category/thumb/'.$category->image);
          File::delete(public_path().'/uploads/category/'.$category->image);

          $category->delete();

          $request->session()->flash('success','Category Deleted Successfully');
 
        //   return response()->json([
        //     'status' => true,
        //     'message' => 'Category Deleted Successfully'    
        // ]);

        return redirect()->route('categories.index');

    }
}


