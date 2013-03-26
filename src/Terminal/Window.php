<?php
namespace Terminal;

class Window
{
    /**
     * @var int|null
     */
    private $width;

    /**
     * @var int|null
     */
    private $height;

    /**
     * @return int|null
     *
     * @throws \RuntimeException
     */
    public function getWidth()
    {
        if (is_null($this->width)) {
            exec('tput cols', $output, $code);
            if ($code || !isset($output[0])) {
                throw new \RuntimeException('Unable to get width of terminal screen');
            }
            $this->width = (int) $output[0];
        }

        return $this->width;
    }

    /**
     * @return int|null
     *
     * @throws \RuntimeException
     */
    public function getHeight()
    {
        if (is_null($this->height)) {
            exec('tput lines', $output, $code);
            if ($code || !isset($output[0])) {
                throw new \RuntimeException('Unable to get height of terminal screen');
            }
            $this->height = (int) $output[0];
        }

        return $this->height;
    }
}
