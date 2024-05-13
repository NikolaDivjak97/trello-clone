<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhaseRequest;
use App\Phase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    public function store(PhaseRequest $request)
    {
        Phase::create([
            'name' => $request->name,
            'board_id' => $request->board_id,
        ]);

        return redirect()->back()->with('success', 'Phase created successfully!');
    }

    public function getCardsComponent(Request $request): JsonResponse
    {
        if(!$request->phase_id) {
            return new JsonResponse(['success' => false, 'message' => 'Missing required fields!']);
        }

        $phase = (new Phase()) -> find($request -> phase_id);

        if(!$phase) {
            return new JsonResponse(['success' => false, 'message' => 'Phase not found!']);
        }

        $html = view('phases.one-phase', compact('phase'))->render();

        return new JsonResponse(['success' => true, 'html' => $html]);
    }
}
