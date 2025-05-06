<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Http\Controllers;

use App\Domains\Task\UseCases\StartTaskUseCase;
use Illuminate\Http\Request;

/**
 * It's responsible for starting the import process.
 *
 * @param Request $request
 *
 * @see StartImportOffersListener
 */
class StartTaskController
{
    public function __invoke(Request $request, StartTaskUseCase $startTaskUseCase)
    {
        $startTaskUseCase->execute(
            []
        );

        return response()->json([
            'message' => 'Task job dispatched',
        ]);
    }
}
