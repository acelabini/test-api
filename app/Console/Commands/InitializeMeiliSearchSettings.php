<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use MeiliSearch\Client;

class InitializeMeiliSearchSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:meili_settings {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize meilisearch settings';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = $this->argument('model');
        try {
            /** @var Project $model */
            $model = new $input;
            $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
            $index = $client->index($model->searchableAs());
            $index->updateSearchableAttributes($model::getSearchableAttributes());
            $index->updateFilterableAttributes(array_keys($model::getFilterableAttributes()));
            $index->updateSortableAttributes(array_keys($model::getSortableAttributes()));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            throw $e;
        }
    }
}
