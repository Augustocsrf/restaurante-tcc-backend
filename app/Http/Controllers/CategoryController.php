<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //Método para obter a lista de todas as categorias completas
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return response($categories, 200);
    }

    //Método para obter o cardápio, com categorias e a lista de items em cada categoria
    public function getMenu()
    {
        $categories = Category::select('id', 'name')
            ->where('active', 1)
            ->orderBy('name', 'ASC')
            ->get();

        foreach ($categories as $category) {
            $items = Item::
            select('id', 'name', 'description', 'price')
            ->where([
                ['active', '=', '1'],
                ['category_id', '=', $category->id],
            ])
            ->orderBy('name', 'ASC')
            ->get();

            $category->items = $items;
        }

        return response($categories->toJson(JSON_PRETTY_PRINT), 200);
    }

    //Atualizar informações da categoria
    public function updateCategory(Request $request, $id)
    {
        if(Category::where('name', $request->name)->exists()){
            return response()->json([
                "message" => "Categoria com esse nome já existe"
            ], 409);
        }

        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->name = is_null($request->name) ? $category->name : $request->name;
            $category->active = is_null($request->active) ? $category->active : $request->active;
            $category->save();

            return response()->json([
                "message" => "Atualizado com sucesso"
            ], 200);
        } else {
            return response()->json([
                "message" => "Categoria não encontrada"
            ], 404);
        }
    }

    public function deleteCategory($id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->delete();

            return response()->json([
                "message" => "Registros deletados"
            ], 202);
        } else {
            return response()->json([
                "message" => "Categoria não encontrada"
            ], 404);
        }
    }

    //Método para criar uma nova categoria
    public function create(Request $request)
    {
        if(Category::where('name', $request->name)->exists()){
            return response()->json([
                "message" => "Categoria com esse nome já existe"
            ], 409);
        }

        $category = new Category;
        $category->name = $request->name;
        $category->active = 1;
        $category->save();

        return response()->json($category, 201);
    }
}
