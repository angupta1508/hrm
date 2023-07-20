<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class CronJob extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        // sendMailForInactiveImages();
    }

}
