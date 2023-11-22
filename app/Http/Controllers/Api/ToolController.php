<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Exception;
use Illuminate\Http\Request;

class ToolController extends Controller
{

    public function getTools()
    {
        try {
            $tools = Tool::all();
            return response()->json($tools);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar ferramentas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getToolsByTag(Request $request)
    {
        try {
            $tag = $request->tag;
            $tools = Tool::where('tags', 'LIKE', '%' . $tag . '%')->get();

            if ($tools->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhuma ferramenta encontrada.'
                ], 404);
            }

            return response()->json($tools);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar ferramentas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function createTool(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'link' => 'required|url',
                'description' => 'required|string',
                'tags' => 'required|array',
            ]);

            $tool = Tool::create(([
                'title' => $validatedData['title'],
                'link' => $validatedData['link'],
                'description' => $validatedData['description'],
                'tags' => json_encode($validatedData['tags']),
            ]));

            return response()->json([
                'message' => 'Ferramenta adicionada!',
                'tool' => $tool,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao adicionar ferramenta.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function deleteTool($id)
    {
        try {
            $tool = Tool::findOrFail($id);
            $tool->delete();

            return response()->json([
                'message' => 'Ferramenta removida!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ferramenta não encontrada.',
                'error' => $e->getMessage(),
            ], 404);
        };
    }
}
