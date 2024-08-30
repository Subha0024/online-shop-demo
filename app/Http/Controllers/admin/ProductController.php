<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit($id)
    {
        //
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
        //
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
