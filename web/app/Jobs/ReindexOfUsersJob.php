<?php

namespace App\Jobs;

use App\Models\OfUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReindexOfUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $modelsToReindex;

    public function __construct($modelsToReindex)
    {
        $this->modelsToReindex = $modelsToReindex;
    }

    public function handle()
    {
        // Reindex logic
        OfUser::whereIn('id', $this->modelsToReindex)->searchable();
    }
}
