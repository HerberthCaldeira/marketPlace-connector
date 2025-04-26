<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

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
class ImportOfferController extends Controller
{
    public function __invoke(Request $request)
    {
        //create as status pending
        $importTask = ImportTask::create();

        /**
         * Dispatch the event to start the import process
         * @see App\Listeners\StartImportOffersListener
         */
        event(new StartImportOffersEvent($importTask));

        return response()->json([
            'message' => 'Import offers job dispatched',
        ]);
    }
}
