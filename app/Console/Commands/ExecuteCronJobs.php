<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\order;

class ExecuteCronJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan semua Job yang waktunya telah tiba.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jobs = DB::table('cron_jobs')->where('execute_at' , '<=' , now())->get();

        foreach($jobs as $j){
            $order = Order::find($j->id_order);
            if($order && $order->anggota) {
                $order->anggota->credit_anggota += 5;
                $order->anggota->save();
            }
            DB::table('cron_jobs')->where('id' , $j->id)->delete();
        }

        Log::info('udah dijalanin');
        $this->info("semua job telah dijalankan");
    }
}
