<?php

namespace SilverShop\ColoredVariations;

use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Queries\SQLInsert;
use SilverStripe\ORM\Queries\SQLSelect;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Migrates existing data from the old "Images" many_many join table
 * (Product_Images) to the renamed "ColorImages" join table (Product_ColorImages).
 *
 * Run this task once after upgrading to the version that renamed the
 * relationship from Images to ColorImages.
 */
class MigrateColorImagesTask extends BuildTask
{
    private static string $segment = 'MigrateColorImagesTask';

    protected string $title = 'Migrate ColoredVariations Images → ColorImages';

    protected static string $description = 'Copies rows from the old Product_Images join table '
        . 'into the new Product_ColorImages join table, preserving ColorID and Sort values. '
        . 'Safe to run multiple times (duplicate rows are skipped).';

    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $oldTable = 'SilverShop_Page_Product_Images';
        $newTable = 'SilverShop_Page_Product_ColorImages';

        if (DB::get_conn() === null) {
            $output->writeln('No database connection – cannot run migration.');
            return Command::FAILURE;
        }

        // Bail out if the old table does not exist (already migrated or fresh install).
        $existingTables = DB::table_list();
        if (!in_array(strtolower($oldTable), array_map('strtolower', $existingTables))) {
            $output->writeln("Old table \"{$oldTable}\" not found – nothing to migrate.");
            return Command::SUCCESS;
        }

        // Ensure the new table exists (it is created by dev/build).
        if (!in_array(strtolower($newTable), array_map('strtolower', $existingTables))) {
            $output->writeln("New table \"{$newTable}\" not found – please run dev/build first.");
            return Command::FAILURE;
        }

        // Copy rows that do not already exist in the new table.
        $inserted = 0;

        $rows = SQLSelect::create(
            ['"SilverShop_Page_ProductID"', '"ImageID"', '"ColorID"', '"Sort"'],
            "\"{$oldTable}\""
        )->execute();

        foreach ($rows as $row) {
            $productId = (int) $row['SilverShop_Page_ProductID'];
            $imageId   = (int) $row['ImageID'];
            $colorId   = (int) ($row['ColorID'] ?? 0);
            $sort      = (int) ($row['Sort'] ?? 0);

            // Skip if already present in the new table.
            $existing = SQLSelect::create(
                ['COUNT(*)'],
                "\"{$newTable}\"",
                ['"SilverShop_Page_ProductID"' => $productId, '"ImageID"' => $imageId]
            )->execute()->value();

            if ($existing > 0) {
                continue;
            }

            SQLInsert::create("\"{$newTable}\"", [
                '"SilverShop_Page_ProductID"' => $productId,
                '"ImageID"'                   => $imageId,
                '"ColorID"'                   => $colorId,
                '"Sort"'                      => $sort,
            ])->execute();

            $inserted++;
        }

        $output->writeln("Migration complete. {$inserted} row(s) copied to \"{$newTable}\".");

        return Command::SUCCESS;
    }
}
