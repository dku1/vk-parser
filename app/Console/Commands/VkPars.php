<?php

namespace App\Console\Commands;

use App\Services\Vk;
use Illuminate\Console\Command;

class VkPars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:friends {vk_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to stars parsing friends for user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('vk_id');
        (new Vk())->getFriend($id, true);
    }
}
