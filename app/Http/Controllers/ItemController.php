<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Item;

class ItemController extends Controller
{
    //
    public function getProductList(){
        $items = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->select('items.*', 'categories.active as category_active', 'categories.name as category_name')
        ->get();

        return response()->json($items, 200);
    }

    public function getProductListPage($page){
        $offset = 10 * ($page - 1);

        $items = DB::table('items')
        ->join('categories', 'categories.id', '=', 'items.category_id')
        ->select('items.*', 'categories.active as category_active', 'categories.name as category_name')
        ->offset($offset)
        ->limit(10)
        ->get();

        return response()->json($items, 200);
    }

    public function delete($id){
        if (Item::where('id', $id)->exists()) {
            $category = Item::find($id);
            $category->delete();

            return response()->json([
                "message" => "Registros deletados"
            ], 202);
        } else {
            return response()->json([
                "message" => "Produto não encontrado"
            ], 404);
        }
    }

    //Método para criar um novo item
    public function create(Request $request)
    {
        $item = new Item;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->active = 1;
        $item->category_id = $request->categoryId;
        $item->save();

        return response()->json([
            "message" => "Item criado"
        ], 201);
    }

    //Método para editar um item
    public function update(Request $request, $id)
    {
        if (Item::where('id', $id)->exists()) {
            $item = Item::find($id);

            $item->name = is_null($request->name) ? $item->name : $request->name;
            $item->description = is_null($request->description) ? $item->description : $request->description;
            $item->price = is_null($request->price) ? $item->price : $request->price;
            $item->active = is_null($request->active) ? $item->active : $request->active;
            $item->category_id = is_null($request->category_id) ? $item->category_id : $request->categoryId;

            $item->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Item não encontrado"
            ], 404);
        }
    }
}
