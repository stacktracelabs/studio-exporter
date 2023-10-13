<?php

namespace App\Console\Commands;

use App\Models\ProductCollection;
use App\Models\StullerCategory;
use Illuminate\Console\Command;

class ExportCollectionsCommand extends Command
{
    protected $signature = 'export:collections';

    protected $description = 'Exports collections';

    public function handle()
    {
        $output = base_path("exports/stuller_web_categories.json");

        if (file_exists($output)) {
            unlink($output);
        }

        /** @var StullerCategory $root */
        $root = StullerCategory::query()->root()->sole();

        $categories = $root->children->map->export()->all();

        file_put_contents($output, json_encode($categories, JSON_PRETTY_PRINT));

        $this->info("Categories exported to $output");

        $output = base_path("exports/product_collections.json");

        if (file_exists($output)) {
            unlink($output);
        }

        /** @var \App\Models\ProductCollection $root */
        $root = ProductCollection::query()->root()->sole();

        $categories = $root->children->map->export()->all();

        file_put_contents($output, json_encode($categories, JSON_PRETTY_PRINT));

        return $this->info("Collections exported to $output");
    }
}
