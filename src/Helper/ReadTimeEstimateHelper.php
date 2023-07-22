<?php

namespace App\Helper;

class ReadTimeEstimateHelper
{
    private const WORDS_PER_MINUTE = 200;
    private const STR_MINUTE = 'мин.';
    private const STR_SECOND = 'сек.';

    private int $minutes;
    private int $seconds;

    /**
     * readTimeEstimate constructor.
     * @param string $str
     */
    public function __construct(string $str)
    {
        $wordCount = $this->wordCount(strip_tags($str));
        $this->minutes = ceil($wordCount / static::WORDS_PER_MINUTE);
        $this->seconds = ceil($wordCount % static::WORDS_PER_MINUTE / (static::WORDS_PER_MINUTE / 60));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return ($this->minutes == 0)    ? $this->seconds . ' ' . static::STR_MINUTE
            : $this->minutes . ' ' .
            static::STR_MINUTE . ', ' .
            $this->seconds . ' ' .
            static::STR_SECOND;
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }

    public function getSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * @param string $str
     * @return int
     */
    protected function wordCount(string $str): int
    {
        $v = preg_split('/\W+/u', $str, -1, PREG_SPLIT_NO_EMPTY);
        return count($v);
    }
}
