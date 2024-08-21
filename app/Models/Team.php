<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function supports()
    {
        return $this->hasMany(Support::class);
    }

    public function settings()
    {
        return $this->hasMany(TeamSetting::class);
    }

    public static function booted()
    {
        static::created(function ($team) {
            $team->settings()->create([
                'key' => 'prompt_for_match',
                'value' => __('Rewrite the question and provide an answer based on the following context:'),
                'description' => 'Prompt for mapping user questions to the FAQ and generating an answer using GPT.',
            ]);

            $team->settings()->create([
                'key' => 'prompt_for_direct',
                'value' => __('Please respond to game questions based on the following database.'),
                'description' => 'Prompt for directly answering user questions using the FAQ.',
            ]);

            $team->settings()->create([
                'key' => 'prompt_for_no_questions',
                'value' => __('Please provide an answer to the following question:'),
                'description' => 'Prompt for answering user questions when no FAQ is available.',
            ]);

            $team->settings()->create([
                'key' => 'webhook_url',
                'value' => '',
                'description' => 'The URL to send webhook events to.',
            ]);

            $team->settings()->create([
                'key' => 'debug_mode',
                'value' => false,
                'description' => 'Whether to enable debug mode for the team.',
            ]);
        });
    }
}
