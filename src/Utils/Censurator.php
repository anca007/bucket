<?php


namespace App\Utils;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Censurator
{
    public function __construct(private readonly ParameterBagInterface $bag)
    {
    }

    public function purify(string $text)
    {
        //return str_replace(self::BAN_WORDS, "******", $text);

        $filename = $this->bag->get('censurator_file');

        if (file_exists($filename)){
            //transforme ma liste en tableau de mots
            $banWords = file($filename);

            foreach ($banWords as $word) {
                //supprime les retours à la ligne
                $word = str_ireplace(PHP_EOL, "", $word);

                if (strpos($text, $word)) {

                    $text = str_ireplace($word, str_repeat('*', strlen($word)), $text);
                }
            }
        }
        return $text;
    }


}
