<?php

namespace App\Console\Commands;

use App\Models\MegaMenu;
use App\Models\ProductCard;
use Illuminate\Console\Command;

class ExportMegaMenuCommand extends Command
{
    protected $signature = 'export:mega-menu {id} {output}';

    protected $description = 'Exports mega menu.';

    public function handle()
    {
        $output = base_path("exports/".$this->argument('output'));

        if (file_exists($output)) {
            unlink($output);
        }

        /** @var MegaMenu $menu */
        $menu = MegaMenu::query()->findOrFail($this->argument('id'));

        $data = [
            'cards' => $menu->cards->map->export()->all(),
            'quick_links' => $menu->quickLinks->map->export(),
            'product_cards' => $menu->productCards->map(function (ProductCard $card) {
                return [
                    'type' => $card->type,
                    'card' => $card->card->export(),
                ];
            })->all(),
        ];

        file_put_contents($output, json_encode($data, JSON_PRETTY_PRINT));

        return $this->info("Exported to $output");
    }
}
