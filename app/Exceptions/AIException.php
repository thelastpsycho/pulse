<?php

namespace App\Exceptions;

use Exception;

enum AIErrorType: string
{
    case NETWORK = 'network';
    case AUTH = 'auth';
    case RATE_LIMIT = 'rate_limit';
    case SERVER = 'server';
    case INVALID_RESPONSE = 'invalid_response';
}

class AIException extends Exception
{
    protected AIErrorType $type;

    public function __construct(string $message, AIErrorType $type)
    {
        parent::__construct($message);
        $this->type = $type;
    }

    public function getType(): AIErrorType
    {
        return $this->type;
    }

    public function getUserMessage(): string
    {
        return match ($this->type) {
            AIErrorType::NETWORK => 'Unable to reach AI service. Please check your connection.',
            AIErrorType::AUTH => 'AI service not configured. Please contact administrator.',
            AIErrorType::RATE_LIMIT => 'Daily AI limit reached. Please try again tomorrow.',
            AIErrorType::SERVER => 'AI service temporarily unavailable. Please try again later.',
            AIErrorType::INVALID_RESPONSE => 'AI service returned unexpected data. Please try again.',
        };
    }
}
