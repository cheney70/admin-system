<?php

namespace Cheney\Content\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

Trait FileTrait
{
    /** 单个文件上传
     * @param $files
     * @param string $pathDir
     * @return array
     */
    public static function fileUpload(UploadedFile $files){
        Log::info("file=".$files);
        $FileName = Str::start(Storage::putFile(self::fileDir(), $files), '/');
        return config('filesystems.disks.local.root'). $FileName;
    }

    /**
     * file dir
     *
     * @return string
     */
    public static function fileDir()
    {
        return env('APP_NAME') . '-' . date('Y-m-d');
    }
}
