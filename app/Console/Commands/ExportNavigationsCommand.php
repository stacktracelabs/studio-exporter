<?php

namespace App\Console\Commands;

use App\Models\Navigation;
use Illuminate\Console\Command;

class ExportNavigationsCommand extends Command
{
    protected $signature = 'export:navigations';

    protected $description = 'Export navigations';

    public function handle()
    {
        $output = base_path("exports/navigations.json");

        if (file_exists($output)) {
            unlink($output);
        }

        $navigations = Navigation::query()->root()->get()->map->export();

        file_put_contents($output, json_encode($navigations, JSON_PRETTY_PRINT));

        return $this->info("Exported to $output");
    }
}
