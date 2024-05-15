<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\SupportService;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    protected $supportService;

    public function __construct(SupportService $supportService)
    {
        $this->supportService = $supportService;
    }

    public function answer(Request $request, Team $team)
    {
        $input = $request->input('question');

        $support = $this->supportService->answer($input, $team);

        return response()->json(['response' => $support]);
    }
}
