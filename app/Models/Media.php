<?php

namespace App\Models;

use Illuminate\Support\Facades\File;

class Media
{
    protected $chunksFolder = 'chunks';
    protected $uploadFolder = 'uploads';
    protected $maxiParts = 200;
    protected $inputName = 'fileUpload';
    protected $fileName = 'fileName';
    protected $totalParts = 'totalParts';

    public static function storeChunk($request)
    {
        $handle = new static;

        $uuid = $request->uuid;
        $partIndex = (int) $request->partIndex;

        try {
            if ($request->totalParts > $handle->maxiParts) {
                return ['error' => 'File is too large.', 'preventRetry' => true];
            }

            $file = $request->file($handle->inputName);
            $file->storeAs($uuid, $partIndex, $handle->chunksFolder);

            return [
                'success' => true,
                'uuid' => $uuid
            ];
        } catch (\Exception $e) {
            $handle->cleanChunkDir($handle->chunksFolder, $uuid);
        }
    }

    public static function combineChunk($request)
    {
        try {
            $handle = new static;
            $uuid = $request->uuid;
            $totalParts = $request[$handle->totalParts];

            $filename = explode('.', $request[$handle->fileName]);
            $extension = array_pop($filename);

            $chunkPath = storage_path($handle->chunksFolder . DIRECTORY_SEPARATOR . $uuid);
            $storePath = $handle->uploadFolder . DIRECTORY_SEPARATOR . $uuid . '.' . $extension;
            $mediaPath = storage_path($storePath);

            $target = fopen($mediaPath, 'wb');

            for ($i = 0; $i < $totalParts; $i++) {
                $chunk = fopen($chunkPath . DIRECTORY_SEPARATOR . $i, 'rb');
                stream_copy_to_stream($chunk, $target);
                fclose($chunk);
            }

            // Success
            fclose($target);
            $handle->cleanChunkDir($uuid);

            $bytes = File::size(storage_path($storePath));
            $exten = File::extension(storage_path($storePath));
            $types = File::type(storage_path($storePath));
            $mimes = File::mimeType(storage_path($storePath));

            $infos = [
                'filename' => $request[$handle->fileName],
                'bytes' => $bytes,
                'extension' => $exten,
                'type' => $types,
                'mime' => $mimes,
                'path' => $uuid . '.' . $exten
            ];

            if (in_array($exten, ['jpg', 'jpeg', 'png'])) {
                $result = array_merge($infos, [
                    'url' => [
                        'small' => route('imagecache', ['template' => 'small', 'filename' => $uuid . '.' . $exten]),
                        'medium' => route('imagecache', ['template' => 'medium', 'filename' => $uuid . '.' . $exten]),
                        'large' => route('imagecache', ['template' => 'large', 'filename' => $uuid . '.' . $exten])
                    ]
                ]);
            } else {
                $result = $infos;
            }

            return ['success' => true, 'uuid' => $uuid, 'fileinfo' => $result];
        } catch (\Exception $e) {
            (new static)->cleanChunkDir($uuid);

            return ['error' => $e->getMessage()];
        }
    }

    public static function destroyMedia($filename)
    {
        $handle = new static;

        if (File::delete(storage_path($handle->uploadFolder . DIRECTORY_SEPARATOR . $filename))) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    protected function cleanChunkDir($uuid)
    {
        try {
            $dir = storage_path($this->chunksFolder . DIRECTORY_SEPARATOR . $uuid);

            $its = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $fls = new \RecursiveIteratorIterator($its, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($fls as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }

            rmdir($dir);
        } catch (\Exception $e) {
            return false;
        }
    }
}
