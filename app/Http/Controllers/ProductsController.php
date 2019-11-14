<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ShoppingCart;
use App\Http\Resources\ProductsCollection;

use Session;
class ProductsController extends Controller
{

    public function __construct() {
        $this->middleware('auth',['except' => 'index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $Data )
    {
        $products = Product::paginate(10);
        if ( $Data->wantsJson() ) {
            return  new ProductsCollection ($products);
        }
        //return view('products.index', ['products' => $products, 'shopping_cart' => $shopping_cart]);
        return view('products.index', ['products' => $products]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product;
        return view('products.create', ["product" =>  $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $options = [
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price
        ];
        if (Product::create( $options)) {
                return redirect('/productos');
        }else {
            return view('products.create');
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
        $product = Product::findOrFail($id);
        return view('products.show', ['product'=>$product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', ["product" =>  $product]);
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
        $product = Product::findOrFail($id);

        $product->title       = $request->title ;
        $product->description = $request->description;
        $product->price       = $request->price;
        if ( $product->save() ) {
            return redirect('/');
        }else{
            return view('products.edit', ["product" =>  $product]);
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
        $product = Product::findOrFail($id);
        $product::destroy($id);
        return redirect('/productos');
    }
}