<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\ProcessSupportMessage;
use App\Models\Team;
use App\Models\TeamSetting;
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

        if (! empty(TeamSetting::get($team->id, 'webhook_url'))) {
            ProcessSupportMessage::dispatch($input, $team);

            return response()->json(['response' => __('Your question has been submitted and will be answered shortly.')]);
        }

        $support = $this->supportService->answer($input, $team);

        return response()->json(['response' => $support]);
    }
}
