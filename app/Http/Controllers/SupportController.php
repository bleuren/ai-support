<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;
use OpenAI\Laravel\Facades\OpenAI;

class SupportController extends Controller
{
    public function answer(Request $request, Team $team)
    {
        $input = $request->input('question');

        $data = $this->handleSupportMessage($input, $team);

        $support = Support::create($data);

        return response()->json(['response' => $support]);
    }

    public function handleSupportMessage($input, Team $team)
    {
        $questions = $team->questions;

        $startTime = microtime(true);

        $questionsArray = $questions->map(function ($question) {
            return $question->question.$question->answer;
        })->toArray();

        if ($questions->isEmpty()) {
            $completionResponse = OpenAI::completions()->create([
                'model' => 'gpt-3.5-turbo-instruct',
                'prompt' => TeamSetting::get($team->id, 'prompt_for_no_questions')." Question: {$input} Answer:",
                'temperature' => 0.9,
                'max_tokens' => 200,
            ]);

            return [
                'team_id' => $team->id,
                'question' => $input,
                'response' => $completionResponse->choices[0]->text.__('Answered by AI', ['model' => 'GPT3.5']),
            ];
        }
        $questionEmbeddings = Cache::remember("question_embeddings_{$team->id}", 3600, function () use ($questionsArray) {
            return OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => $questionsArray,
            ])->embeddings;
        });

        $userQuestionEmbedding = Cache::remember('user_question_embedding_'.md5($input), 3600, function () use ($input) {
            return OpenAI::embeddings()->create([
                'model' => 'text-embedding-3-small',
                'input' => [$input],
            ])->embeddings[0]->embedding;
        });

        $bestMatchIndex = $this->findBestMatchIndex($questionEmbeddings, $userQuestionEmbedding);

        if ($bestMatchIndex['similarity'] >= 0.5) {
            $bestMatchFaq = $questions[$bestMatchIndex['index']];
            $prompt = TeamSetting::get($team->id, 'prompt_for_match')." Context: {$bestMatchFaq->question}{$bestMatchFaq->answer} Question: {$input} Answer:";
            $completionResponse = OpenAI::completions()->create([
                'model' => 'gpt-3.5-turbo-instruct',
                'prompt' => $prompt,
                'temperature' => 0.9,
                'max_tokens' => 200,
            ]);

            $response = $completionResponse->choices[0]->text.__('Answered by AI', ['model' => 'GPT3.5[id: '.($bestMatchIndex['index'] + 1).', similarity: '.Number::percentage($bestMatchIndex['similarity'] * 100, 2).']']);
        } else {
            $completionResponse = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => TeamSetting::get($team->id, 'prompt_for_direct'),
                    ],
                    [
                        'role' => 'system',
                        'content' => json_encode($questions),
                    ],
                    [
                        'role' => 'user',
                        'content' => "Question: {$input} Answer:",
                    ],
                ],
            ]);
            $response = $completionResponse->choices[0]->message->content.__('Answered by AI', ['model' => 'GPT4']);
        }

        return [
            'team_id' => $team->id,
            'question' => $input,
            'response' => $response,
            'meta' => [
                'time' => microtime(true) - $startTime,
                'bestMatchIndex' => $bestMatchIndex ?? 'N/A',
            ],
        ];
    }

    private function findBestMatchIndex($questionEmbeddings, $userQuestionEmbedding)
    {
        $bestMatchIndex = -1;
        $bestSimilarity = 0;

        foreach ($questionEmbeddings as $index => $questionEmbedding) {
            $similarity = $this->cosineSimilarity($questionEmbedding->embedding, $userQuestionEmbedding);
            if ($similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestMatchIndex = $index;
            }
        }

        return ['index' => $bestMatchIndex, 'similarity' => $bestSimilarity];
    }

    private function cosineSimilarity($vectorA, $vectorB)
    {
        $dotProduct = array_sum(array_map(function ($a, $b) {
            return $a * $b;
        }, $vectorA, $vectorB));
        $normA = sqrt(array_sum(array_map(function ($a) {
            return $a * $a;
        }, $vectorA)));
        $normB = sqrt(array_sum(array_map(function ($b) {
            return $b * $b;
        }, $vectorB)));

        return $dotProduct / ($normA * $normB);
    }
}
