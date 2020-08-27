<?php

namespace Matok\Bundle\MediaBundle\Gregwar;

use Gregwar\Image\Image as GregwarImage;

class Image extends GregwarImage
{
    private $focusX;
    private $focusY;


    public function prettyResize($newWidth, $newHeight)
    {
       // dump('pretty');
        $halfOriginalWidth = $this->width() / 2;
        $halfOriginalHeight = $this->height() / 2;
        list($centerX, $centerY) = $this->getFocusPoint($this->width(), $this->height());

        //dump($centerX, $centerY);
      //  dump($halfOriginalHeight, $halfOriginalWidth);
        if ($newWidth > $newHeight) {
            $ratio = $this->width() / $newWidth;

            $cropHeight = (int) ($newHeight * $ratio);

            //dump($centerX - $halfOriginalWidth, $centerY - $cropHeight/2,  $this->width(), $cropHeight);
            if ($centerY - $cropHeight/2 >= 0) {
                $this->crop($centerX - $halfOriginalWidth, $centerY - $cropHeight/2,  $this->width(), $cropHeight)
                    ->resize($newWidth, $newHeight);
            } else {
                $ratio = $this->height() / $newHeight;
                $cropWidth = (int) ($newWidth * $ratio);
                $this->crop($centerX - $cropWidth/2, 0,  $cropWidth, $this->height())
                    ->resize($newWidth, $newHeight);
            }
        } else {
            $ratio = $this->height() / $newHeight;

            $cropWidth = (int) ($newWidth * $ratio);

        //    dump($centerX - $cropWidth/2, $centerY - $halfOriginalHeight,  $this->height(), $cropWidth);
          //  dump($newWidth, $newHeight);
            $this->prettyCrop($centerX - $cropWidth/2, $centerY - $halfOriginalHeight,  $this->height(), $cropWidth)
                ->resize($newWidth, $newHeight);
        }

        //exit;
        return $this;
    }

    private function prettyCrop($x, $y, $width, $height)
    {
        $originalWidth = $this->width();
        $originalHeight = $this->height();

        if ($originalHeight - ($y + $height) < 0) {
            $y = 0;
        }

        $this->crop($x, $y, $width, $height);

        return $this;
    }

    /**
     * @param $width
     * @param $height
     * @return array
     */
    protected function getFocusPoint($width, $height)
    {
        if (empty($this->focusX)) {
            return array((int)$width/2, (int) $height/2);
        }

        return array((int) $this->focusX, (int)$this->focusY);
    }

    /**
     * @param $focus
     *
     * @return self
     */
    public function setFocus($focus = null)
    {
        if (!empty($focus)) {
            $this->focusX = $focus[0];
            $this->focusY = $focus[1];
        }

        return $this;
    }
}