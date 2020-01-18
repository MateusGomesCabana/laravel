<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    private $path= 'images/category';

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
        //selec * from categories
        $categories= Category::paginate(10);
        return view('category.index', compact('categories'));
    }

    //cria a view para visualizar
    public function add()
    {
        return view('category.add');
    }

    /**
     * todo parametro post deve ter um request de parametro
     * pega a reques para que eu possa realiza a trataiva
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        //dd mata a aplicação e formata  e mostra  conteudo da variaver
        //dd(variavel);
        //empty verifica se o valor da variavel é nulo ou vazio -> 0,"",null
        if (!empty($request ->file('image')) && $request->file('image')->isValid()) {
           //crio um nome para o arquivo timestamp + a extensao do arquivo 
            $fileName = time().'.'.$request ->file('image')->getClientOriginalExtension();
            //move o arquivo da pasta temporaria e move para o servidor com o novo nome
            $request ->file('image')->move($this ->path,$fileName);
        }
        //usa o model de categoria para salvar no banco de dados
        //é uma função estática por isso uso :: não preciso usar o new  ela não processa  construct ou o index, já o -> uso para ter acesso as funções de dentro da classe. Ela funciona como se fosse um new ele assa pelo construct e pelas bibliotecas
        $result = Category::create([
            'name' => $request->input('name'),
            'image' =>$fileName
        ]);
        //redireciona para alguma rota do sistema
        return redirect()->route('category.index');
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
        //
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
     * nome da variavel id é o mesmo nome da variavel que é passado na url
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //select * from categoria where id = $id
        $category= Category::find($id);
        //se eu nao tive a categoria ele retorna para a tela de index
        if (!$category) {
            return redirect()->route('category.index');
        }
        return view('category.edit', compact('category'));
    }

    /**
     * se eu nao quiser passar uma variavel por paramentro eu igualo ela como nuo   
     */
    public function update(Request $request, $id)
    {
        $fileName = null;

        if (!empty($request ->file('image')) && $request->file('image')->isValid()) {
            //delet  o arquivo no servidor
            if (!empty($request ->input('deleteimage'))&& file_exists($this->path . '/' . $request->input('deleteimage'))) {
                //deleto  o arquivo do servidor
                unlink($this->path . '/' . $request->input('deleteimage'));
            }        
            $fileName = time().'.'.$request ->file('image')->getClientOriginalExtension();
            $request ->file('image')->move($this ->path,$fileName);
        }
        //faz a atualização d arquivo com as informações
        if(!$fileName){
            $update = [
                'name'  => $request->input('name')
            ];
        }else{
             $update = [
                'name'  => $request->input('name'),
                'image' => $fileName
            ];
        }

        //procura no banco a categoria para fazer a atualização
        $result = Category::find($id)->update($update);
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     * acertar
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
         $category = Category::find($id);
         if($category){
            //detach deleta o registro que tem a relação muito para muitos, deleto o registro da pivot table
            $category->products()->detach();
            $result = $category->delete();

         }
         return redirect()->route('category.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
         $name = $request->input('name');
         $search = TRUE;
         if($name){
            //primeira é a coluna a segunda é a condição, terceira é o parametro 
            //se eu tive outro where só colocar antes do ->get(), ->where(); 
            $categories = Category::where('name','like','%' . $name . '%')->get();
         }
        return view('category.index', compact('categories','search'));
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
