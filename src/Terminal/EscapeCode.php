<?php
namespace Terminal;

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
}
