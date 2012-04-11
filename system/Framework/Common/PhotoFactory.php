<?php
namespace Framework\Common;

/**
 * GD Helper
 */
class PhotoFactory
{
    protected $currentImage = array();

    /**
     * @param string $file absolute file path
     * @return array
     * @throws \InvalidArgumentException
     */
    public function open($file)
    {
        if (!file_exists($file))
            throw new \InvalidArgumentException();

        $this->flushMemory();

        list($w, $h) = getimagesize($file);

        $filedata = pathinfo($file);

        $ext = str_replace('jpg', 'jpeg', strtolower($filedata['extension']));
        $create = 'imagecreatefrom' . $ext;
        $source = $create($file);

        $this->currentImage = array(
            'resource' => $source,
            'width' => $w,
            'height' => $h,
            'extension' => $ext,
            'filedata' => $filedata,
        );

        return $this->currentImage;
    }

    /**
     * @param string $filename
     * @param int $maxFileSizeMb
     * @param int $uniqueId
     * @param string $extRegExp
     * @return array
     * @throws \InvalidArgumentException|\OutOfRangeException|\RuntimeException|\UnexpectedValueException
     */
    public function upload($filename, $maxFileSizeMb = 3, $uniqueId = 0, $extRegExp = '/jpe?g|gif|png/i')
    {
        $this->flushMemory();

        if (isset($_FILES[$filename]) && $_FILES[$filename]['error'] == 0)
        {
            $photo = &$_FILES[$filename];
            $filedata = pathinfo($photo['name']);

            if (!preg_match($extRegExp, $filedata['extension']))
                throw new \InvalidArgumentException('Wrong image type');

            if ($photo['size'] > $maxFileSizeMb * 1024 * 1024)
                throw new \OutOfRangeException("Max image size - {$maxFileSizeMb}mb");

            $file = APPLICATION_PATH . 'tmp/' . md5($photo['name'] . $photo['tmp_name'] . $uniqueId);

            if (move_uploaded_file($photo['tmp_name'], $file))
            {
                list($w, $h) = getimagesize($file);

                $ext = str_replace('jpg', 'jpeg', strtolower($filedata['extension']));
                $create = 'imagecreatefrom' . $ext;
                $source = $create($file);

                unlink($file);

                $this->currentImage = array(
                    'resource' => $source,
                    'width' => $w,
                    'height' => $h,
                    'extension' => $ext,
                    'filedata' => $filedata,
                );

                return $this->currentImage;
            }
            else
            {
                throw new \RuntimeException('move_uploaded_file return false');
            }
        }
        else
        {
            throw new \UnexpectedValueException("File $filename did not uploaded");
        }
    }

    /**
     * @param int $needleWidth needle image width
     * @param null|resource $sourceImage
     * @param null|int $sourceWidth
     * @param null|int $sourceHeight
     * @throws \InvalidArgumentException
     * @return resource
     */
    public function intellectualResizedByWidth(
        $needleWidth, $sourceImage = null, $sourceWidth = null, $sourceHeight = null)
    {
        if (is_null($sourceImage))
        {
            if (!isset($this->currentImage['resource'])) throw new \InvalidArgumentException();
            $sourceImage = $this->currentImage['resource'];
            $sourceWidth = $this->currentImage['width'];
            $sourceHeight = $this->currentImage['height'];
        }

        $needleHeight = $sourceHeight / $sourceWidth * $needleWidth;
        $resized = imagecreatetruecolor($needleWidth, $needleHeight);
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0,
            $needleWidth, $needleHeight, $sourceWidth, $sourceHeight);

        return $resized;
    }

    /**
     * @param int $needleHeight needle image height
     * @param null|resource $sourceImage
     * @param null|int $sourceWidth
     * @param null|int $sourceHeight
     * @throws \InvalidArgumentException
     * @return resource
     */
    public function intellectualResizedByHeight(
        $needleHeight, $sourceImage = null, $sourceWidth = null, $sourceHeight = null)
    {
        if (is_null($sourceImage))
        {
            if (!isset($this->currentImage['resource'])) throw new \InvalidArgumentException();
            $sourceImage = $this->currentImage['resource'];
            $sourceWidth = $this->currentImage['width'];
            $sourceHeight = $this->currentImage['height'];
        }

        $needleWidth = $sourceWidth / $sourceHeight * $needleHeight;
        $resized = imagecreatetruecolor($needleWidth, $needleHeight);
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0,
            $needleWidth, $needleHeight, $sourceWidth, $sourceHeight);

        return $resized;
    }

    /**
     * @param int $side size of square side (e.g. 50 = avatar 50x50)
     * @param null|resource $sourceImage
     * @param null|int $sourceWidth
     * @param null|int $sourceHeight
     * @throws \InvalidArgumentException
     * @return resource
     */
    public function asSquareAvatar(
        $side, $sourceImage = null, $sourceWidth = null, $sourceHeight = null)
    {
        if (is_null($sourceImage))
        {
            if (!isset($this->currentImage['resource'])) throw new \InvalidArgumentException();
            $sourceImage = $this->currentImage['resource'];
            $sourceWidth = $this->currentImage['width'];
            $sourceHeight = $this->currentImage['height'];
        }

        $aspectWidth = $side - 1;
        $aspectHeight = 0;

        while ($aspectHeight < $side)
            $aspectHeight = floor($sourceHeight / $sourceWidth * ++$aspectWidth);

        $resized = imagecreatetruecolor($aspectWidth, $aspectHeight);
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0,
            $aspectWidth, $aspectHeight, $sourceWidth, $sourceHeight);

        $avatar = imagecreatetruecolor($side, $side);
        imagecopyresampled($avatar, $resized, 0, 0, 0, 0,
            $side, $side, $side, $side);

        imagedestroy($resized);

        return $avatar;
    }

    /**
     * @param string $target relative file path
     * @param null|resource $sourceImage
     * @param null|string $type jpeg, png, gif, wbmp [default by currentImage extension]
     * @param bool $flushMemory Will memory flush?
     * @param null|int $quality image quality for jpeg and png (null recommended)
     * @param null|mixed $filter PNG filters or WBMP foreground
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function save($target, $sourceImage = null, $type = null, $flushMemory = false,
           $quality = null, $filter = null)
    {
        if (is_null($sourceImage))
        {
            if (!isset($this->currentImage['resource'])) throw new \InvalidArgumentException();
            $sourceImage = $this->currentImage['resource'];
            $type = (!is_null($type)) ? $type : $this->currentImage['extension'];
        }

        switch ($type)
        {
            case 'jpg': case 'jpeg': $r = imagejpeg($sourceImage, APPLICATION_PATH . $target, $quality); break;
            case 'png': $r = imagepng($sourceImage, APPLICATION_PATH . $target, $quality, $filter); break;
            case 'gif': $r = imagegif($sourceImage, APPLICATION_PATH . $target); break;
            case 'wbmp': $r = imagewbmp($sourceImage, APPLICATION_PATH . $target, $filter); break;
            default: return false;
        }

        if ($flushMemory) $this->flushMemory();

        return $r;
    }

    public function flushMemory() {
        if (isset($this->currentImage['resource']))
            imagedestroy($this->currentImage['resource']);
        $this->currentImage = array();
    }
}
