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
     * @var bool
     */
    private $autoFlush;

    /**
     * @param bool $autoFlush
     */
    public function __construct($autoFlush = false)
    {
        $this->autoFlush = (bool) $autoFlush;
    }

    /**
     * @return bool
     */
    public function isAutoFlush()
    {
        return $this->autoFlush;
    }

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

    public function write($string, $hideOverflow = true)
    {

    }

    public function flush()
    {

    }
}
