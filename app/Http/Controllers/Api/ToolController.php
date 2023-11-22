<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Exception;
use Illuminate\Http\Request;

class ToolController extends Controller
{
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
}
