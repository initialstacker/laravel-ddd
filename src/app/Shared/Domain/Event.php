<?php declare(strict_types=1);

namespace App\Shared\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    /**
     * Combines traits for event dispatching, WebSocket interaction, and model serialization.
     */
    use Dispatchable, InteractsWithSockets, SerializesModels;
}
