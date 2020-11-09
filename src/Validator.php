<?php


namespace Baki;

use Illuminate\Support\Str;

class Validator
{
    protected function validateParameters()
    {
        if (empty($this->save_path))
            throw new \Exception('Please set save path!');
        if (empty($this->size))
            throw new \Exception('Please set max file size!');

        return true;
    }

    protected function validateMimeType($fileMimeType)
    {
        if (empty($this->mimeType) || in_array($fileMimeType, $this->mimeType) || $this->isMimeTypeInArray($fileMimeType))
            return true;
        throw new \Exception('File must be type: ' . implode(' or ', $this->mimeType));
    }

    protected function isMimeTypeInArray($fileMimeType)
    {
        $contains = false;
        foreach ($this->mimeType as $value)
            if (Str::contains($fileMimeType, $value))
                $contains = true;
        return $contains;
    }

    protected function validateFileSize($fileSize)
    {
        if ($fileSize <= $this->size)
            return true;
        throw new \Exception('File size must be less than '.$this->size.'MB!');
    }

    protected function validateSavePath()
    {
        if (!is_dir($this->save_path))
            mkdir($this->save_path);
        return true;
    }

    protected function upload($fileTmpPath, $newFileName)
    {
        return (move_uploaded_file($fileTmpPath, $this->save_path . $newFileName)) ? $newFileName : false;
    }
}