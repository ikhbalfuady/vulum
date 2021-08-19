<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UploadFile{
  protected $filePath = '';

  protected $file = null;
  protected $disk = null;
  protected $filename = null;

  public function __construct($file){
    $this->file = $file;
  }

  protected function randFilename(){
    $filename = Carbon::now()->timestamp.'_'.uniqid().'.'.$this->getExt();
    return $filename;
  }

  public function upload(){
    $filename = $this->getFilename();
    $file = $this->file;
    return Storage::disk($this->disk)->putFileAs($this->filePath, $file, $filename, 'public');
  }

  public function setPath($location){
    if(substr($location, -1) != '/') $location .= '/';
    $this->filePath = $location;
    return $this;
  }

  public function setDisk($disk) {
    $this->disk = $disk;
    return $this;
  }

  public function getPath(){
    return $this->filePath;
  }

  public function setFilename($filename){
    $this->filename = $filename.'.'.$this->getExt();
  }

  public function getFilename(){
    if(is_null($this->filename)){
      $this->filename = $this->randFilename();
    }
    return $this->filename;
  }

  public function getClientFilename(){
    return $this->file ? $this->file->getClientOriginalName() : null;
  }

  public function getExt(){
    $file = $this->file;
    return strtolower($file->getClientOriginalExtension());
  }

  public function getSize() {
    return $this->file ? $this->file->getSize() : null;
  }
}
