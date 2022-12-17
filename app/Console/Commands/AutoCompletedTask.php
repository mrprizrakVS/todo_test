<?php

namespace App\Console\Commands;

use App\Models\Tasks;
use Illuminate\Console\Command;

class AutoCompletedTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $tasks = Tasks::where('created_at', '<=', now()->subMinutes(5))->where('status', '!=', true)->get();

        foreach ($tasks as $task) {
            $task->status = true;
            if ($task->save()) {
                $this->info($task->id);
            }
        }


    }
}
