<?php

namespace App\Console\Commands;

use App\Models\Entities\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticlesTableReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles-table:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets the articles table values to default.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		DB::update('update `articles` set `deleted_at` = NULL');
		DB::update('update `articles` set `visible` = 0 where `default` = 0');
		DB::update('update `articles` set `visible` = 1 where `default` = 1');

		echo "\nArticles table reset done.";
    }
}
