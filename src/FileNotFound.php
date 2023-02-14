<?php

namespace ArtARTs36\FileSystem\Arrays;

class FileNotFound extends \Exception implements \ArtARTs36\FileSystem\Contracts\FileNotFound
{
    public function __construct(
        private string $filepath,
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getInvalidFilePath(): string
    {
        return $this->filepath;
    }
}
