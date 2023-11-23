<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Exception;
use Illuminate\Http\Request;


/**
 * @OA\Info(
 *     title="Documentação de API para desafio backend",
 *     version="1.0.0"
 * )
 */
class ToolController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tools",
     *     summary="Obter todas as ferramentas",
     *     tags={"Ferramentas"},
     *     security={{"bearer_token": {}}},
     *     @OA\Response(
     *         response="200",
     *         description="Lista de todas as ferramentas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Tool")
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Não autorizado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Erro ao buscar ferramentas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro ao buscar ferramentas."),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro específica.")
     *         )
     *     )
     * )
     */
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
