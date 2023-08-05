<?php

namespace App\Nova\Actions;

use Exception;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneratePostDescription extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public $name = 'Generate post description';

    public function handle(ActionFields $fields, Collection $posts) : Collection
    {
        foreach ($posts as $post) {
            try {
                (new \App\Actions\GeneratePostDescription)->generate($post);

                $this->markAsFinished($post);
            } catch (Exception $e) {
                $this->markAsFailed($post, $e);
            }
        }

        return $posts;
    }
}