<?php

namespace App\Jobs;

use App\Http\Controllers\Api\VisionController;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class CloudVision implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId= $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $images = DB::table('images')->where('userId',$this->userId)->select('imageId')->get()->toArray();
        foreach ($images as $image){
            VisionController::magic((string)$image->imageId,'1',$this->userId);
            VisionController::magic((string)$image->imageId,'2',$this->userId);
            VisionController::magic((string)$image->imageId,'3',$this->userId);
        }
    }
}
