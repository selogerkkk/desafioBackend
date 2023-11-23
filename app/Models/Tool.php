<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Tool",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="link", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class Tool extends Model
{
    use HasFactory;

    protected $table = 'Tools';
    protected $fillable = [
        'title',
        'link',
        'description',
        'tags'
    ];
}
