<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Domains\Task\UseCases\StartTaskUseCase;
use App\Events\StartImportOffersEvent;
use App\Models\ImportTask;
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
        $startTaskUseCase->execute([
            $request->validated()
        ]);

        return response()->json([
            'message' => 'Import offers job dispatched',
        ]);
    }
}
