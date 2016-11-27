<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function randomPassword($length,
                        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;

    if ($max < 1) 
    {
        return(FALSE);
    }

    for ($i = 0; $i < $length; ++$i) 
    {
        $str .= $keyspace[random_int(0, $max)];
    }

    return $str;
}