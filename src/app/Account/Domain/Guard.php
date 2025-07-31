<?php declare(strict_types=1);

namespace App\Account\Domain;

enum Guard: string
{
    /**
     * API guard for stateless API authentication.
     */
    case API = 'api';

    /**
     * Web guard for session-based web authentication.
     */
    case WEB = 'web';
}
