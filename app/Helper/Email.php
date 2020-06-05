<?php


namespace App\Helper;


class Email
{
    static function replaceUmlauts($emailAdress) {
        if(!empty($emailAdress))
        {
            $emailExplode = explode("@", $emailAdress);
            if(count($emailExplode) == 2) {
                return $emailExplode[0]."@".idn_to_utf8($emailExplode[1],IDNA_DEFAULT,INTL_IDNA_VARIANT_UTS46 );
            }
        }
    }
}
