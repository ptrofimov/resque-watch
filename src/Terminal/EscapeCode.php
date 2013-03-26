<?php
namespace Terminal;

/**
 * @see http://invisible-island.net/xterm/ctlseqs/ctlseqs.html
 * @see http://ascii-table.com/ansi-escape-sequences.php
 */
class EscapeCode
{
    public function smcup()
    {
        return "\033[?1049h";
    }

    public function rmcup()
    {
        return "\033[?1049l";
    }

    /**
     * Set/reset auto-wrap mode
     *
     * @param bool $flag
     *
     * @return string
     */
    public function autoWrap($flag)
    {
        return $flag ? "\033[?7h" : "\033[?7l";
    }

    public function screenSize()
    {
        return "\033[18t";
    }
}
