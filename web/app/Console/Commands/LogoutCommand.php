<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class LogoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Logout from the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Auth::guard('admin')->logout();
        $this->info('You have been logged out.');
    }
}
