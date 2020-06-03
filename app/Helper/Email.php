<?php


namespace App\Helper;


class Email
{
    static function replaceUmlauts($emailAdress) {
        return str_replace(["xn--4ca","xn--nda","xn--tda"], ["ä","ö", "ü"], $emailAdress);
    }
}
