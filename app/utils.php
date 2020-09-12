<?php
if (! function_exists('clip_difficulty'))
{
    function clip_difficulty($diff)
    {
        return round($diff >= 400 ? $diff : 400 / exp(1.0 - $diff / 400));
    }
}