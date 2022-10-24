<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $categories=new Category();
        $categories = Category::pluck('category_name', 'id')->toArray();
      
        $products=Product::paginate(3);
        return view('products',compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $category = Category::with('product')->where('id'=='category_id')->get();
        // dd($category);

         
        // $category = Category::with('category')->get();
        // 
        $product=new Product();


        $product->name= $request->has('name')? $request->get('name'):'';
        $product->price= $request->has('price')? $request->get('price'):'';
        $product->quantity= $request->has('quantity')? $request->get('quantity'):'';
        $product->size= $request->has('size')? $request->get('size'):'';
        $product->brand= $request->has('brand')? $request->get('brand'):'';
        $product->details= $request->has('details')? $request->get('details'):'';
        $product->category_id= $request->has('category_id')? $request->get('category_id'):'';
        $product->is_active= 1;
        
        if($request->hasFile('images')){
            $files = $request->file('images');

            $imageLocation= array();
            $i=0;
            foreach ($files as $file){
                $extension = $file->getClientOriginalExtension();
                $fileName= 'product_'. time() . ++$i . '.' . $extension;
                // dd($fileName);
                // $location= '/images/uploads/';
                $file->move(storage_path('app/public/products/') , $fileName);
                $imageLocation[]=  $fileName;
            }

            $product->images= implode('|', $imageLocation);
            // $product->category()->save($category);
            $product->save();
         
       
            return back()->with('success', 'Product Successfully Saved!');
        } else{
            return back()->with('error', 'Product was not saved Successfully!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $images=explode('|',$product->images);
        return view('product_details',compact('product','images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
      //
    }
    public function addProduct(){
        $products= Product::all();
        $categories = Category::pluck('category_name', 'id')->toArray();
        // dd($products);
        $returnProducts= array();
        foreach ($products as $product){
            $images= explode('|', $product->images);

            $returnProducts[] = [
               'name'=> $product->name,
               'price'=> $product->price,
               'quantity'=> $product->quantity,
               'images'=> $images[0],
               'brand'=>$product->brand,
               'size'=>$product->brand,
               'details'=>$product->details,
               'id'=>$product->id
              
            ];

        }
        // return $returnProducts;

        //  dd($returnProducts);
        return view('add_product', compact('returnProducts','categories'));
    }
}
