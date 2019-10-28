<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            $re = file_get_contents('http://wechat.18022480300.com/wechat/youjia');
            $result = json_decode($re,1);
            foreach($result['result'] as $v){
                if(Cache::has($v['city'].":data")){
                    $msg = $v['92h']."\n".$v['95h']."\n";
                    Cache::put($v['city'].':data',$msg); //更新
                }
            }
            $last_day = date('Y-m-d',strtotime('-1 days')); //昨天的数据
            $last_data = Cache::get($last_day);
            $last_result = json_decode($last_data,1);
            foreach ($result['result'] as $k=>$v){
                if(($last_result['result'][$k]['92h'] != $v['92h']) || ($last_result['result'][$k]['95h'] != $v['95h'])){
                    //数据不一致
                    //创建模板消息
                    print_r($v);
                }
            }

        })->daily();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
