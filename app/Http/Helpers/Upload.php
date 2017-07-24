<?php namespace App\Http\Helpers;

use Auth;
use Storage;
use Image;

class Upload {

    protected $file;
    protected $info;

    /**
     * [upload description]
     * @param  [file] $file [description]
     * @param  [string] $path [description]
     * @return [obj] $this  [description]
     */
    public function upload($file, $path)
    {
        $this->file = $file;
        $this->info['pathFile'] = $this->file->store($path);

        return $this;
    }

    /**
     * [uploadTemp description]
     * @param  [file] $file [description]
     * @return [obj] $this [description]
     */
    public function uploadTemp($file)
    {
        $this->file = $file;
        $this->info['pathFile'] = $this->file->store('temp/'.Auth::id());

        return $this;
    }
    /**
     * [moveFromTemp description]
     * @param  [string] $from [description]
     * @param  [string] $to   [description]
     * @return [obj] $this [description]
     */
    public function moveFromTemp($from, $to, $name=null)
    {
        if (is_null($name)) {
            $infoFile = pathinfo($from);
            $to = $to.'/'.$infoFile['basename'];
        }

        Storage::move($from, $to);

        $this->file = Storage::get($to);
        $this->info['pathFile'] = $to;

        return $this;
    }

    /**
     * [resize description]
     * @param  [number] $size [description]
     * @return [obj] $this [description]
     */
    public function resize($size)
    {
        $this->getInfo();

        $img = Image::make($this->file)
            ->widen($size)
            ->encode($this->info['extension']);

        Storage::put($this->info['pathFile'], $img);

        return $this;
    }

    /**
     * [getInfo description]
     * @return [array] [description]
     */
    public function getInfo()
    {
        $infoFile = pathinfo($this->info['pathFile']);
        $this->info = $this->info + $infoFile;

        return $this->info;
    }
}
