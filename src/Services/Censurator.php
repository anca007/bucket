<?php

namespace App\Services;

class Censurator
{
    const BAN_WORDS = ['michel', 'extincteur', 'chocolatine'];

    public function purify(string $text){

        return  str_ireplace(self::BAN_WORDS, "*****", $text);
    }

}