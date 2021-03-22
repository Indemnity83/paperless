<?php

namespace App\Http\Controllers;

use App\Models\Obj;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $object = $this->object($request->get('o'))
                       ->firstOrFail();

        return response()->view('browse.show', [
            'object' => $object,
        ]);
    }

    /**
     * @param $hash
     * @return mixed
     */
    protected function object($hash)
    {
        if ($hash === null) {
            return Obj::isRoot();
        }

        return Obj::byHash($hash);
    }
}
