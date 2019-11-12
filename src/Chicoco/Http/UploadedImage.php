<?php

namespace Chicoco\Http;

use Chicoco\Core\Exceptions\FileException;

class UploadedImage extends UploadedFile
{
    protected $width;
    protected $height;
    protected $mime;
    protected $validTypes = [
        IMAGETYPE_GIF,
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG
    ];

    public function __construct(array $FILE)
    {
        parent::__construct($FILE);

        if (!$this->isValid()) {
            throw new FileException('invalid image type');
        }
    }

    private function isValid()
    {
        $metadata = getimagesize($this->tmpName);

        if (!isset($metadata['mime'])) {
            return false;
        }

        if (!in_array($metadata[2], $this->validTypes)) {
            return false;
        }

        $this->mime = $metadata['mime'];
        $this->width = $metadata[0];
        $this->height = $metadata[1];
        $this->extension = image_type_to_extension($metadata[2], false);

        return true;
    }
}
