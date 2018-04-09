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
    protected $imageId;
    protected $part;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId,$imageId,$part)
    {
        $this->userId= $userId;
        $this->imageId = $imageId;
        $this->part = $part;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            VisionController::magic((string)$this->imageId,$this->part,$this->userId);
    }
}
