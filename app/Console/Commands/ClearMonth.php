<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ClearMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除月排名数据';

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
     * @return mixed
     */
    public function handle()
    {
        //
       $this->removeRedis();

    }

    public function removeRedis(){
        Redis::DEL('person_month_rank');
        Redis::DEL('group_month_rank');
        \Log::info('清除月排名数据！');
    }
}
