<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Team;
use App\Services\SupportService;
use Livewire\Component;

class SupportChat extends Component
{
    public $question;

    public $response;

    public $teamId;

    protected $rules = [
        'question' => 'required|string|max:255',
    ];

    public function mount($team = null)
    {
        $this->teamId = $team ?? 1;
    }

    public function submit(SupportService $supportService)
    {
        $this->validate();

        $team = Team::findOrFail($this->teamId);

        $support = $supportService->answer($this->question, $team);

        $this->response = $support->response;
    }

    public function render()
    {
        return view('livewire.support-chat');
    }
}
