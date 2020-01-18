<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use DB;
use Validator;

class ProductController extends Controller
{
    private $path= 'images/product';
    //construtos de produtos
    public function __construct()
    {
        //manda para o middleware para saber se estou autenticado
        $this->middleware('auth');
    }  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //::get tras todos os produtos ativos no banco, não usei o paginate
        $products = Product::get();
        $categories = Category::get();
        //index não há nada a ser buscado por isso eu passo o array vazio
        $selected_cat = [];
        return view('product.index', compact('products','categories','selected_cat'));
    }

     //cria a view para visualizar
    public function add()
    {
        //tenho que colocar no compact todas as categorias ativas para poder mostrar na tela
        $categories = Category::get();
        return view('product.add',compact('categories'));
    }
    /**
     * todo parametro post deve ter um request de parametro
     * pega a reques para que eu possa realiza a trataiva
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
       $validator = Validator::make($request->all(),
        ['name'=>'required|min:10|max:255',
         'description' => 'required']);
       if(!$validator->fails()){
          $images = $request->file('images');
          //crio de uma forma estática
          $product = Product::create([
            'name' =>$request->input('name'),
            'description' => $request -> input('description')
          ]);
          if (!empty($product)&&!empty($images)) {
              //key o numero da linha e row é o conteudo
              foreach ($images as $key => $row) {
                  if (!empty($row)) {
                      $fileName = time() . $key . '.' . $row->getClientOriginalExtension();
                      $row->move($this->path,$fileName);
                      $image = new ProductImages;
                      $image->product_id = $product ->id;
                      $image ->image = $fileName;
                      $image->save();
                  }
              }
          }
          //faz o insert na pivot table   produto ->pivot->categoria, atraves da sync eu consigo inserir na pivot
          $product->categories()->sync($request->input('category'));
       }
       return redirect()->route('product.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //verifica se o prduto existe
        $product = Product::find($id);
        if (!empty($product)) {
           $categories = Category::get();

           $images = ProductImages::where('product_id',$product->id)->get();
           $selected_cat = array();
           //relacionament de produo com categoria
           foreach ($product ->categories as $category) {
               //pivot -> category id pega o id da categoria,  caegory retorna todo conteudo, funcionaria se eu colocar category->category_id
               $selected_cat[]= $category->pivot->category_id;
               //$selected_cat[]= $category->category_id;
           }
           return view('product.edit', compact('product','categories','selected_cat','images'));
        }
        return redirect()->route('product.index');

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
        //faço a buscca das categorias e imagens  e veifico a existencia do produto
        $images = $request->file('images');
        $category = $request ->input('category');
        $product= Product::find($id);
        if (!empty($product)) {
            if (!empty($images)) {
                foreach ($images as $key => $row) {
                    if (!empty($row)) {
                        $fileName = time() . $key . '.' . $row->getClientOriginalExtension();
                        $row->move($this->path,$fileName);
                        $image = new ProductImages;
                        $image->product_id = $product->id;
                        $image->image = $fileName;
                        $image->save();
                    }
                }
            }
            if(!empty($category)){
                $product ->categories()->sync($category);
            }
            $product->update([
                'name'=> $request->input('name'),
                'description'=>$request->input('description')
            ]);
        }
        return redirect()->route('product.index');
        
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
