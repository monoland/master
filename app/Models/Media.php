<?php

namespace App\Models;

use Illuminate\Support\Facades\File;

class Media
{
    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public static function storeChunk($request)
    {
        try {
            if ($request->totalparts > 200) {
                return ['error' => 'File is too large.', 'preventRetry' => true];
            }

            $file = $request->file('media');

            // total part
            $uuid = $request->uuid;

            $partindex = (int) $request->partindex;

            $file->storeAs($uuid, $partindex, 'chunks');

            return ['success' => true, 'uuid' => $uuid];
        } catch (\Exception $e) {
            (new static)->cleanChunkDir($uuid);

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public static function combineChunk($request)
    {
        try {
            $uuid = $request->uuid;
            $totalParts = $request->totalparts;

            $filename = explode('.', $request->filename);
            $extension = array_pop($filename);

            $storage = $request->has('storage') ? $request->storage : 'media';

            $chunkPath = storage_path('chunks' . DIRECTORY_SEPARATOR . $uuid);
            $storePath = $storage . DIRECTORY_SEPARATOR . $uuid . '.' . $extension;
            $mediaPath = storage_path($storePath);

            $target = fopen($mediaPath, 'wb');

            for ($i = 0; $i < $totalParts; $i++) {
                $chunk = fopen($chunkPath . DIRECTORY_SEPARATOR . $i, 'rb');
                stream_copy_to_stream($chunk, $target);
                unlink($chunkPath . DIRECTORY_SEPARATOR . $i);
                fclose($chunk);
            }

            // Success
            fclose($target);
            rmdir($chunkPath);

            $bytes = File::size(storage_path($storePath));
            $exten = File::extension(storage_path($storePath));
            $types = File::type(storage_path($storePath));
            $mimes = File::mimeType(storage_path($storePath));

            $infos = [
                'filename' => $request->filename,
                'bytes' => $bytes,
                'extension' => $exten,
                'type' => $types,
                'mime' => $mimes,
                'path' => $uuid . '.' . $exten
            ];

            return ['success' => true, 'uuid' => $uuid, 'fileinfo' => $infos];
        } catch (\Exception $e) {
            (new static)->cleanChunkDir($uuid);

            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $filename
     * @return void
     */
    public static function destroyMedia($filename)
    {
        if (File::delete(storage_path('media' . DIRECTORY_SEPARATOR . $filename))) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    /**
     * Undocumented function
     *
     * @param [type] $uuid
     * @return void
     */
    protected function cleanChunkDir($uuid)
    {
        $dir = storage_path('chunks' . DIRECTORY_SEPARATOR . $uuid);

        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }
}
