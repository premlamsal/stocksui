<?php

namespace App\Http\Controllers;

use App\DeliveryNote;
use Illuminate\Http\Request;

use App\Http\Resources\DeliveryNoteResource as DeliveryNoteResource;


class DeliveryNoteController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::user()->id);

        $store_id = $user->stores[0]->id;

        return DeliveryNoteResource::collection(DeliveryNote::where('store_id', $store_id)->with('invoiceDetail')->orderBy('updated_at', 'desc')->paginate(8));
    }
}
