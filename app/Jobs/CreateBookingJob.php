<?php

namespace App\Jobs;

use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\AvailabilityRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;

class CreateBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected int $userId;

    public function __construct(array $data, int $userId)
    {
        $this->data = $data;
        $this->userId = $userId;
    }

    public function handle(
        BookingRepositoryInterface $bookingRepository,
    ): void {
        DB::beginTransaction();

        try {
            $isAvailable = $bookingRepository->isAvailable(
                $this->data['property_id'],
                $this->data['start_date'],
                $this->data['end_date']
            );

            if (! $isAvailable) {
                DB::rollBack();
                return;
            }

            $bookingRepository->create([
                ...$this->data,
                'user_id' => $this->userId,
            ]);

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
