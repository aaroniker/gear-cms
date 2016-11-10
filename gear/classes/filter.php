<?php

class filter {

    protected static $umlauts = ['Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß'];
    protected static $umlautsReplace = ['Ae', 'ae', 'Oe', 'oe', 'Ue', 'ue', 'ss'];
    protected static $accents = ['Š' => 'S', 'š' => 's', 'Ð' => 'Dj','Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss','à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f'];

    public static function xss($string) {

        if (is_string($string)) {
            $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }

        return $string;

    }

    public static function url($string) {

        $string = str_replace(self::$umlauts, self::$umlautsReplace, $string);
        $string = strtr($string, self::$accents);
        $string = strtolower($string);
        $string = preg_replace('/[^a-zA-Z0-9\s]/', '-', $string);
        $string = preg_replace('{ +}', ' ', $string);
        $string = trim($string);
        $string = str_replace(' ', '-', $string);

        return $string;

    }

    public static function file($string) {

        $string = str_replace(self::$umlauts, self::$umlautsReplace, $string);
        $string = strtr($string, self::$accents);
        $string = preg_replace('/[^.a-zA-Z0-9\s]/', '-', $string);
        $string = preg_replace('{ +}', ' ', $string);
        $string = trim($string);
        $string = str_replace(' ', '-', $string);

        return $string;

    }

    public static function compress($string) {

        $string = str_replace(["\n","\r"], '', $string);
        $string = preg_replace('!\s+!',' ', $string);
        $string = str_replace([' {', ' }', '{ ', '; '], ['{', '}', '{', ';'], $string);

        return $string;

    }

}

?>
