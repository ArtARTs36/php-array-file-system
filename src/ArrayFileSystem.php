<?php

namespace ArtARTs36\FileSystem\Arrays;

use ArtARTs36\FileSystem\Contracts\FileSystem;

/**
 * @phpstan-type FilePath string
 * @phpstan-type FileContent string
 * @phpstan-type FileParams array{content: FileContent, updated_at: \DateTimeInterface, is_dir: bool, permissions: int}
 */
class ArrayFileSystem implements FileSystem, \Countable
{
    /** @var array<FilePath, FileParams> */
    protected array $files = [];

    public function removeFile(string $path): bool
    {
        if (! $this->exists($path)) {
            throw new FileNotFound($path);
        }

        unset($this->files[$path]);

        return true;
    }

    public function removeDir(string $path): bool
    {
        $path = $this->prepareDir($path);

        $isDir = $this->files[$path]['is_dir'] ?? null;

        if ($isDir === null) {
            throw new FileNotFound($path);
        } elseif ($isDir === false) {
            throw new FileNotFound($path, sprintf('Attempt to removing file with path "%s"', $path));
        }

        unset($this->files[$path]);

        return true;
    }

    public function createDir(string $path, int $permissions = 0755): bool
    {
        $path = $this->prepareDir($path);

        $this->files[$path] = [
            'content' => '',
            'permissions' => $permissions,
            'updated_at' => new \DateTime(),
            'is_dir' => true,
        ];

        return true;
    }

    public function exists(string $path): bool
    {
        return array_key_exists($path, $this->files);
    }

    public function createFile(string $path, string $content): bool
    {
        $this->files[$path] = [
            'content' => $content,
            'permissions' => 0755,
            'updated_at' => new \DateTime(),
            'is_dir' => false,
        ];

        return true;
    }

    public function reset(): self
    {
        $this->files = [];

        return $this;
    }

    public function getFileContent(string $path): string
    {
        if (! $this->exists($path)) {
            throw new FileNotFound($path);
        }

        return $this->files[$path]['content'];
    }

    public function getLastUpdateDate(string $path): \DateTimeInterface
    {
        if (! $this->exists($path)) {
            throw new FileNotFound($path);
        }

        return $this->files[$path]['updated_at'];
    }

    public function getFromDirectory(string $path): array
    {
        $path = $this->prepareDir($path);

        $find = [];

        foreach (array_keys($this->files) as $file) {
            if (str_contains($file, $path)) {
                $find[] = $file;
            }
        }

        return $find;
    }

    /**
     * @return array{path: FilePath, content: FileContent}|null
     */
    public function firstFile(): ?array
    {
        $path = array_key_first($this->files);

        if ($path === null) {
            return null;
        }

        return ['path' => $path, 'content' => $this->files[$path]['content']];
    }

    public function count(): int
    {
        return count($this->files);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function downPath(string $path): string
    {
        return dirname($path);
    }

    public function getAbsolutePath(string $path): string
    {
        return $path;
    }

    public function getTmpDir(): string
    {
        return '/tmp/';
    }

    private function prepareDir(string $dir): string
    {
        return rtrim($dir, DIRECTORY_SEPARATOR);
    }
}
