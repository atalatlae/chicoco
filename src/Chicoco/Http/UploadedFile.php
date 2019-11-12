<?php

namespace Chicoco\Http;

use Chicoco\Core\Exceptions\FileException;

class UploadedFile
{
    protected $name;
    protected $mimeType;
    protected $tmpName;
    protected $error;
    protected $size;
    protected $extension;

    public function __construct(array $FILE)
    {

        if (empty($FILE['name'])) {
            $this->error = UPLOAD_ERR_NO_FILE;
            throw new FileException('missing filename');
        }

        if (!is_uploaded_file($FILE['tmp_name'])) {
            throw new FileException('invalid uploaded file');
        }

        $this->name = $FILE['name'];
        $this->mimeType = $FILE['type'];
        $this->tmpName = $FILE['tmp_name'];
        $this->error = $FILE['error'];
        $this->size = $FILE['size'];
        $this->extension = pathinfo($this->name, PATHINFO_EXTENSION);
    }

    public function moveTo(string $to)
    {

        $dir = pathinfo($to, PATHINFO_DIRNAME);

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        if (!is_writable($dir)) {
            throw new FileException('destination is not writable');
        }

        if (!move_uploaded_file($this->tmpName, $to)) {
            throw new FileException('error moving the file');
        }
        
        return true;
    }

    public function __get(string $name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }
}
