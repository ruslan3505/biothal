<?php

namespace App\Http\Controllers;

use App\Models\DistributionOffer;
use App\Http\Requests\CreateOffer;

class DistributionOfferController extends Controller
{
    public function createOffer(CreateOffer $request) {

        $offer = DistributionOffer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'message' => $request->text
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно отправили запрос'
        ]);
    }
}
