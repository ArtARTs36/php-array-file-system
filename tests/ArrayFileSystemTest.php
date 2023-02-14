<?php

namespace ArtARTs36\FileSystem\Arrays\Tests;

use ArtARTs36\FileSystem\Arrays\ArrayFileSystem;
use PHPUnit\Framework\TestCase;

final class ArrayFileSystemTest extends TestCase
{
    /**
     * @covers \ArtARTs36\FileSystem\Arrays\ArrayFileSystem::createFile
     */
    public function testCreateFile(): void
    {
        $fs = new ArrayFileSystem();

        $fs->createFile('aa', 'bb');

        self::assertEquals('bb', $fs->getFileContent('aa'));
    }

    /**
     * @covers \ArtARTs36\FileSystem\Arrays\ArrayFileSystem::isEmpty
     */
    public function testIsEmpty(): void
    {
        $fs = new ArrayFileSystem();

        $fs->createFile('aa', 'bb');

        self::assertFalse($fs->isEmpty());
    }

    public function providerForTestGetFromDirectory(): array
    {
        return [
            [
                [
                    '/super-dir/file1.txt' => 'aa',
                    '/super-dir/file2.txt' => 'bb',
                ],
                '/super-dir',
            ],
            [
                [
                    '/super-dir/file1.txt' => 'aa',
                    '/super-dir/file2.txt' => 'bb',
                ],
                '/super-dir/',
            ],
        ];
    }

    /**
     * @covers \ArtARTs36\FileSystem\Arrays\ArrayFileSystem::getFromDirectory
     * @dataProvider providerForTestGetFromDirectory
     */
    public function testGetFromDirectory(array $files, string $dir): void
    {
        $fs = new ArrayFileSystem();

        foreach ($files as $path => $content) {
            $fs->createFile($path, $content);
        }

        self::assertEquals(array_keys($files), $fs->getFromDirectory($dir));
    }
}
