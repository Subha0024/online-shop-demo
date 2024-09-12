<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('id','ASC')->with('product_images');

        if($request->get('keyword')){
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }
        $products = $products->paginate();
         //dd($products);
         return view('admin.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        return view('admin.products.create',compact('categories','brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->image_array);
        // exit();
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
           //  'status' => 'required'

        ];

         if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
               $rules['qty'] = 'required|numeric';
         }

        $validator = Validator::make($request->all(),$rules);

         if($validator->passes()){

             $product = new Product();
             $product->title = $request->title;
             $product->slug = $request->slug;
             $product->description = $request->description;
             $product->price = $request->price;
             $product->compare_price = $request->compare_price;
             $product->category_id = $request->category;
             $product->sub_category_id = $request->sub_category;
             $product->brand_id	= $request->brand;
             $product->is_featured = $request->is_featured;
             $product->sku = $request->sku;
             $product->barcode = $request->barcode;
             $product->track_qty = $request->track_qty;
             $product->qty = $request->qty;
             $product->status = $request->status;
             $product->save();

             ////// Save Gallery Pics /////

              if(!empty($request->image_array)){

                foreach($request->image_array as $temp_image_id){

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray);  //// like jpg,jpeg,gif,png etc

                      $productImage = new ProductImage();
                      $productImage->product_id = $product->id;
                      $productImage->image = 'NULL';
                      $productImage->save();

                      $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                      $productImage->image = $imageName;
                      $productImage->save();

                      ///// Generate Product Thumbnail  /////
                        
                      ///// Large Image /////
                      $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                      $destPath = public_path().'/uploads/product/large/'.$imageName;
                      $image = Image::make($sourcePath);
                      $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                        });
                       $image->save($destPath);

                      ///// Small Image /////
                      $destPath = public_path().'/uploads/product/small/'.$imageName;
                      $image = Image::make($sourcePath);
                      $image->fit(300,300);
                      $image->save($destPath);

                }

              }



             $request->session()->flash('success','Product Added Successfully');

             return response()->json([
                 'status' => true,
                 'message' => 'Product Added Successfully'
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
        $product = Product::find($id);

        if(empty($product)){
             return redirect()->route('products.index')->with('error','Product Not Found');
        }

     /////  Fetch Product Images  /////
         $productImages = ProductImage::where('product_id',$product->id)->get();

        $subcategories = SubCategory::where('category_id',$product->category_id)->get();
          //dd($subcategories);
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        return view('admin.products.edit',compact('categories','brands','product','subcategories','productImages'));
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
        $product = Product::find($id);
        
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
           //  'status' => 'required'

        ];

         if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
               $rules['qty'] = 'required|numeric';
         }

        $validator = Validator::make($request->all(),$rules);

         if($validator->passes()){

             $product->title = $request->title;
             $product->slug = $request->slug;
             $product->description = $request->description;
             $product->price = $request->price;
             $product->compare_price = $request->compare_price;
             $product->category_id = $request->category;
             $product->sub_category_id = $request->sub_category;
             $product->brand_id	= $request->brand;
             $product->is_featured = $request->is_featured;
             $product->sku = $request->sku;
             $product->barcode = $request->barcode;
             $product->track_qty = $request->track_qty;
             $product->qty = $request->qty;
             $product->status = $request->status;
             $product->save();

             ////// Save Gallery Pics /////

             

             $request->session()->flash('success','Product Updated Successfully');

             return response()->json([
                 'status' => true,
                 'message' => 'Product Updated Successfully'
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
    public function destroy($id)
    {
        //
    }
}
