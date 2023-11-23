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
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         required=false,
     *         description="Tag para filtrar as ferramentas",
     *         @OA\Schema(type="string")
     *     ),
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

    public function getTools(Request $request)
    {
        try {
            $tag = $request->query('tag');
            if ($tag) {
                $tools = Tool::where('tags', 'LIKE', '%' . $tag . '%')->get();
            } else {
                $tools = Tool::all();
            }

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

    /**
     * @OA\Post(
     *     path="/api/tools",
     *     summary="Criar uma nova ferramenta",
     *     tags={"Ferramentas"},
     *     security={{"bearer_token": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "link", "description", "tags"},
     *             @OA\Property(property="title", type="string", example="Nome da Ferramenta"),
     *             @OA\Property(property="link", type="string", format="url", example="https://exemplo.com"),
     *             @OA\Property(property="description", type="string", example="Descrição da ferramenta."),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"tag1", "tag2"})
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Ferramenta adicionada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ferramenta adicionada!"),
     *             @OA\Property(property="tool", ref="#/components/schemas/Tool")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro de validação."),
     *             @OA\Property(property="errors", type="object", example={"field_name": {"Error message"}})
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Erro ao adicionar ferramenta",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro ao adicionar ferramenta."),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro específica.")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Delete(
     *     path="/api/tools/{id}",
     *     summary="Remover uma ferramenta",
     *     tags={"Ferramentas"},
     *     security={{"bearer_token": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da ferramenta a ser removida",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Ferramenta removida com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ferramenta removida!")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Ferramenta não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ferramenta não encontrada."),
     *             @OA\Property(property="error", type="string", example="Mensagem de erro específica.")
     *         )
     *     )
     * )
     */
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
