<?php


namespace Baki;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Uploader extends Validator
{
    protected $request, $file, $mimeType, $save_path, $random_name, $size, $delimiter;
    public function __construct(Request $request, $file, $delimiter = '-', $random_name = false)
    {
        if (gettype($random_name) != 'boolean') throw new \Exception('Parameter "random_name" must be type boolean [true/false]');
        if (strlen($delimiter) > 1) throw new \Exception('Parameter "delimiter" must be single chart');
        $this->request      = $request;
        $this->file         = $file;
        $this->random_name  = $random_name;
        $this->delimiter    = str_replace(['/', '\\', '.', ',', ';', ':'], '-', $delimiter);
    }

    public function setMimeType()
    {
        foreach (func_get_args() as $arg)
            $this->mimeType[] = $arg;
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
        $this->validateParameters();
        if (isset($this->request->{$this->file}))
        {
            $request_file   = $this->request->{$this->file};
            $single_file    = !is_array($request_file) ? true : false;
            $request_file   = !is_array($request_file) ? [$request_file] : $request_file;
            $data_returns   = [];

            foreach ($request_file as $item)
            {
                $fileTmpPath  = $item->getPathname();
                $fileSize     = method_exists($item, 'getClientSize') ? $item->getClientSize() : filesize($item->getPathname());
                $fileSize     = round($fileSize / 1048576, 2);
                $fileMimeType = $item->getClientMimeType();
                $newFileName  = ($this->random_name ? (Str::random(8) . $this->delimiter . Str::random(8) . $this->delimiter) : '') . $item->getClientOriginalName();
                $newFileName  = str_replace([' ', '+', '-', '_'], $this->delimiter, $newFileName);

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
}