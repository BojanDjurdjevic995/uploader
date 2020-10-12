<?php


namespace Baki;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Uploader
{
    protected $request, $file, $mimeType, $save_path, $random_name, $size, $delimiter;
    public function __construct(Request $request, $file, $delimiter = '-', $random_name = false)
    {
        $this->request = $request;
        $this->file = $file;
        $this->random_name = $random_name;
        $this->delimiter = $delimiter;
    }

    public function setMimeType()
    {
        foreach (func_get_args() as $arg)
            if (Str::contains($arg, '/'))
                $this->mimeType[] = $arg;
            else throw new \Exception('The mime type must contain "/". Eg. type/extension (video/mp4, image/jpg)');

    }

    public function setFileMaxSize($size)
    {
        $this->size = $size;
    }

    public function setSavePath($save_path)
    {
        $save_path .= Str::endsWith($save_path, '/') ? '' : '/';
        $this->save_path = $save_path;
    }

    public function save()
    {
        $this->validate();
        if (isset($this->request->{$this->file}))
        {
            $request_file   = $this->request->{$this->file};
            $single_file    = !is_array($request_file) ? true : false;
            $request_file   = !is_array($request_file) ? [$request_file] : $request_file;
            $data_returns   = [];

            foreach ($request_file as $item)
            {
                $fileTmpPath  = $item->getPathname();
                $fileSize     = round($item->getClientSize() / 1048576, 2);
                $fileMimeType = $item->getClientMimeType();
                $newFileName  = ($this->random_name ? (Str::random(8) . '-' . Str::random(8) . '-') : '') . $item->getClientOriginalName();
                $newFileName  = str_replace(' ', $this->delimiter, $newFileName);

                $this->validateMimeType($fileMimeType);
                $this->validateFileSize($fileSize);
                $this->validateSavePath();
                $data_returns[] = $this->upload($fileTmpPath, $newFileName);
            }
            return $single_file ? $data_returns[0] : $data_returns;
        } else {
            throw new \Exception('Please choose a file!');
        }
    }

    protected function validate()
    {
        if (empty($this->save_path))
            throw new \Exception('Please set save path!');
        if (empty($this->size))
            throw new \Exception('Please set max file size!');

        return true;
    }

    protected function validateMimeType($fileMimeType)
    {
        if (empty($this->mimeType) || in_array($fileMimeType, $this->mimeType))
            return true;
        throw new \Exception('File must be type: ' . implode(' or ', $this->mimeType));
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
        if (move_uploaded_file($fileTmpPath, $this->save_path . $newFileName))
            return $newFileName;
        else
            return false;
    }
}