<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\SupportMessageProcessed;
use App\Models\Team;
use App\Models\TeamSetting;
use App\Services\SupportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessSupportMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $input;

    protected $team;

    protected $questionId;

    public function __construct($input, Team $team)
    {
        $this->input = $input;
        $this->team = $team;
    }

    public function handle(SupportService $supportService)
    {
        $support = $supportService->answer($this->input, $this->team);

        Http::post(TeamSetting::get($this->team->id, 'webhook_url'), [
            'question' => $this->input,
            'answer' => $support,
        ]);

        // broadcast(new SupportMessageProcessed($support, $this->input));
    }
}
