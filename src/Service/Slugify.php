<?php

namespace App\Service;


class Slugify
{
    public function generate(string $input): string
    {
        $slug=trim(strtolower($input));
        $slug = str_replace([' ', 'à', 'é', 'è', 'ê', 'ë', 'ù', 'ü', 'î', 'ï', 'ç', '!', ':', '.', ';', ',', '?', '/', '\\'], ['-', 'a', 'e', 'e', 'e', 'e', 'u', 'u', 'i', 'i', 'c', '', '', '', '', '', '', '', ''], $slug);
        $slug=preg_replace('#-{2,}#', '-', $slug);
        return $slug;
    }
}
