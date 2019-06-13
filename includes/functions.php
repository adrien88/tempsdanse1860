<?php

function save_ini_file(string $filename, array $array = [])
{
    $string = '';

    ##! asort will send sub array at last
    asort($array);

    foreach ( $array as $key => $value ) {

        $string .= "\n";
        if(is_array($value)) {

            $string .= "\n['$key']";

            foreach ( $value as $subkey => $subvalue ) {
                $subkey = is_int($subkey) ? "'$subkey'" : $subkey;
                $subvalue = is_array($subvalue) ? serialize($subvalue) : $subvalue;
                $string .= "\n\t $subkey = \"$subvalue\" ";
            }
        }
        else {
            $string .= "'$key' = \"$value\" ";
        }
    }
    return file_put_contents($filename, $string);
}
