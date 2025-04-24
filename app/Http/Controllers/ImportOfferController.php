<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Events\ImportOffersRequestedEvent;
use Illuminate\Http\Request;

class ImportOfferController extends Controller
{
    public function __invoke(Request $request)
    {
        /**
         * Dispatch the event to start the import process
         * @see ImportOffersRequestedListener
         */
        event(new ImportOffersRequestedEvent());

        return response()->json([
            'message' => 'Import offers job dispatched',
        ]);
    }
}
