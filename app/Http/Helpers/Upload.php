<?php namespace App\Http\Helpers;

use Auth;
use Storage;
use Image;

class Upload {

    protected $file;
    protected $info;

    /**
     * [upload description]
     * @param  [file]   $file  [description]
     * @param  [string] $path  [description]
     * @return [obj]    $this  [description]
     */
    public function upload($file, $path)
    {
        $this->file = $file;

        $path = $this->file->store($path);

        $this->getInfo($path);

        return $this;
    }

    /**
     * [uploadTemp description]
     * @param  [file] $file [description]
     * @return [obj]  $this [description]
     */
    public function uploadTemp($file)
    {
        $this->file = $file;

        $path = $this->file->store('temp/'.Auth::id());

        $this->getInfo($path);

        return $this;
    }
    /**
     * [move description]
     * @param  [string] $from [path]
     * @param  [string] $to   [path]
     * @return [obj]    $this [description]
     */
    public function move($from, $to)
    {
        $this->getInfo($from);

        $to = $to.'/'.$this->info['basename'];

        Storage::move($from, $to);

        $this->getInfo($to);

        return $this;
    }

    /**
     * [resize description]
     * @param  [integer] $width [pixels]
     * @param  [integer] $height [pixels]
     * @return [obj]    $this [description]
     */
    public function resize($width=null, $height=null)
    {
        $img = Image::make($this->file)
                    ->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode();

        $this->info['size'] = strlen((string) $img);

        Storage::put($this->info['path'], $img);

        return $this;
    }

    /**
     * [thumbnail description]
     * @param  [integer] $width [pixels]
     * @param  [integer] $height [pixels]
     * @return [obj]    $this [description]
     */
    public function thumbnail($width=null, $height=null)
    {
        $img = Image::make($this->file)
                    ->fit($width, $height, function ($constraint) {
                        $constraint->upsize();
                    })
                    // ->resize($width, $height, function ($constraint) {
                    //     $constraint->aspectRatio();
                    //     $constraint->upsize();
                    // })
                    // ->crop($width, $height)
                    ->encode();

        Storage::put($this->info['dirname'].'/thumbnail_'.$this->info['basename'], $img);

        return $this;
    }

    /**
     * [getData description]
     * @return [array] [description]
     */
    public function getData()
    {
        return $this->info;
    }

    /**
     * [getInfo description]
     * @param  [string] $path   [description]
     * @return [obj]    $this   [description]
     */
    private function getInfo($path)
    {
        $this->info = pathinfo($path);
        $this->info['path'] = $path;
        $this->info['type'] = Storage::mimeType($path);
        $this->info['size'] = Storage::size($path);

        if (!$this->file)
            $this->file = Storage::get($path);

        return $this;
    }
}
