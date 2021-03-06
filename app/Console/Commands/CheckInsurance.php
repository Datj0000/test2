<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\ImportDetail as ImportDetailModel;
use App\Models\Notification as NotificationModel;

class CheckInsurance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insurance:cron';

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
    public function handle()
    {
        $today = Carbon::now()->floorMonth();
        $detail = ImportDetailModel::query()->get();
        if($detail->toArray()){
            foreach ($detail->toArray() as $key => $item){
                $date_end = Carbon::parse($item['date_end'])->floorMonth();
                if($today->lt($date_end) && $today->diffInMonths($date_end) == 1){
                    $check = NotificationModel::query()->where('status','=',0)->where('importdetail_id','=',$item['id'])->first();
                    if(!$check){
                        NotificationModel::query()->create([
                            'importdetail_id' => $item['id'],
                            'status' => 0,
                        ]);
                    }
                }
                else if($today->gt($date_end)){
                    $check = NotificationModel::query()->where('status','=',1)->where('importdetail_id','=',$item['id'])->first();
                    if(!$check){
                        NotificationModel::query()->create([
                            'importdetail_id' => $item['id'],
                            'status' => 1,
                        ]);
                    }
                }
            }
        }
    }
}
