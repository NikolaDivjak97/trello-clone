<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function store(LabelRequest $request)
    {
        (new Label()) -> create([
            'board_id' => $request -> label_board_id,
            'name' => $request -> label_name,
            'color' => $request -> label_color
        ]);

        return back();
    }
}
