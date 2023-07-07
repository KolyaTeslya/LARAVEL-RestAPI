<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;

class ProcessItem implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $item;

    /**
     * Create a new job instance.
     *
     * @param  Item  $item
     * @return void
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = $this->item;

        $item->status = 'processed';
        $item->save();

        // Логирование или отправка уведомления о завершении обработки элемента

        // Вывод информации в консоль для проверки работы очереди
        info('Item processed: ' . $item->id);
    }
}
