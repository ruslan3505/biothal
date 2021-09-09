<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin\Products\GlobalSales;
use App\Models\Admin\Products\GroupSales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getGlobalSales()
    {
        $globalSales = GlobalSales::where('active', 1)->orderBy('sum_modal')->get();

        return response()->json($globalSales);
    }

    public function getGroupSales()
    {
        $groupSales = GroupSales::where('active', 1)->orderBy('sum')->get();

        return response()->json($groupSales);
    }
}
