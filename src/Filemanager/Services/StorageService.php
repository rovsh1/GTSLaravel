<?php

namespace Gsdk\Filemanager\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class StorageService
{
    private static $storage;

    private string $cd = '';

    public static function useStorage(string $storage): void
    {
        self::$storage = Storage::disk($storage);
    }

    public static function storage()
    {
        return self::$storage;
    }

    public function path(string $path = null)
    {
        $path = trim($path, '/');
        return self::$storage->path($this->cd . ($path ? DIRECTORY_SEPARATOR . $path : ''));
    }

    public function url(string $path)
    {
        return self::$storage->url($path);
    }

    public function cd(string $path): static
    {
        if (!str_starts_with($path, '/')) {
            $path = $this->cd . DIRECTORY_SEPARATOR . $path;
        }

        if (!empty($path) && !is_dir(self::$storage->path($path))) {
            throw new \Exception('Директория [' . $path . '] не существует');
        }

        $this->cd = rtrim($path, DIRECTORY_SEPARATOR);

        return $this;
    }

    public function upload(UploadedFile $file)
    {
        $path = $this->path();
        $name = self::checkFilename($path, $file->getClientOriginalName());
        $file->storeAs($this->cd, $name, 'upload');
        //FileService::chmod(self::getPathFilename($path, $name), 'file');
    }

    public function mkdir(string $folderName): void
    {
        if (!self::checkName($folderName)) {
            throw new \Exception('Некорректное имя папки');
        }

        $path = $this->path($folderName);

        if (is_dir($path)) {
            throw new \Exception('Директория [' . $path . '] уже существует');
        }

        mkdir($path, 0770);
        //FileService::chmod($path, 'dir');
    }

    public function rename(string $prevName, string $newName): void
    {
        self::validateName($prevName);
        self::validateName($newName);

        $prevFile = $this->path($prevName);
        if (!file_exists($prevFile)) {
            throw new \Exception('File [' . $prevName . '] not exists');
        }

        $newFile = $this->path($newName);
        if (file_exists($newFile)) {
            throw new \Exception('File [' . $newName . '] already exists');
        }

        rename($prevFile, $newFile);
    }

    public function moveToPath(string $name, string $path): void
    {
        $prevFilename = $this->path($name);
        $movePath = self::$storage->path($path);

        rename($prevFilename, $movePath . DIRECTORY_SEPARATOR . $name);
    }

    public function unlink(string $path, bool $deep = false): void
    {
        $filename = $this->path($path);
        if (!file_exists($filename)) {
            throw new \Exception('File [' . $filename . '] not exists');
        }

        if (is_dir($filename)) {
            if (!$deep) {
                return;
            }

            $objects = scandir($filename);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    $this->unlink($path . DIRECTORY_SEPARATOR . $object, true);
                }
            }

            rmdir($filename);
        } else {
            unlink($filename);
        }
    }

    public static function validatePath(string $path): string
    {
        if (!self::checkPath($path)) {
            throw new \Exception('Path [' . $path . '] format failed');
        }

        return $path;
    }

    public static function validateName(string $name): string
    {
        if (!self::checkName($name)) {
            throw new \Exception('Name [' . $name . '] format failed');
        }

        return $name;
    }

    public static function checkPath(string $path): bool
    {
        return empty($path) || $path === '/' || preg_match('/[^?*:;{}\\\]+$/', $path);
    }

    public static function checkName(string $name): bool
    {
        return !empty($name) && preg_match('/[^\/?*:;{}\\\]+$/', $name);
    }

    private static function checkFilename($path, $name): string
    {
        $filename = $path . DIRECTORY_SEPARATOR . $name;
        if (!file_exists($filename)) {
            return $name;
        }

        $info = pathinfo($name);

        if (preg_match('/^(.*) \((\d+)\)$/', $info['filename'], $m)) {
            return self::checkFilename($path, $m[1] . ' (' . ($m[2] + 1) . ').' . $info['extension']);
        } else {
            return self::checkFilename($path, $info['filename'] . ' (1).' . $info['extension']);
        }
    }
}
