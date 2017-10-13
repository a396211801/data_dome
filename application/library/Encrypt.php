<?php

class Encrypt
{

    private $mcrypt_rijndael = MCRYPT_RIJNDAEL_256;
    private $mcrypt_mode = MCRYPT_MODE_CBC;
    private $mcrypt_rand = MCRYPT_RAND;
    protected static $_instance=NULL;


    public static function initialise()
    {
        if (NULL === self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function encode( $string, $key = '' )
    {
        $key = md5( $key );
        $enc = $this->mcrypt_encode( $string, $key );
        return base64_encode( $enc );
    }

    public function decode( $string, $key = '' )
    {
        $key = md5( $key );

        if (preg_match( '/[^a-zA-Z0-9\/\+=]/', $string ))
        {
            return FALSE;
        }

        $dec = base64_decode( $string );

        if (($dec = $this->mcrypt_decode( $dec, $key )) === FALSE)
        {
            return FALSE;
        }

        return $dec;
    }

    private function mcrypt_encode( $data, $key )
    {
        $init_size = mcrypt_get_iv_size( $this->mcrypt_rijndael, $this->mcrypt_mode );
        $init_vect = mcrypt_create_iv( $init_size, $this->mcrypt_rand );
        return $this->add_cipher_noise( $init_vect . mcrypt_encrypt( $this->mcrypt_rijndael, $key, $data, $this->mcrypt_mode, $init_vect ), $key );
    }

    private function mcrypt_decode( $data, $key )
    {
        $data = $this->remove_cipher_noise( $data, $key );
        $init_size = mcrypt_get_iv_size( $this->mcrypt_rijndael, $this->mcrypt_mode );

        if ($init_size > strlen( $data ))
        {
            return FALSE;
        }

        $init_vect = substr( $data, 0, $init_size );
        $data = substr( $data, $init_size );
        return rtrim( mcrypt_decrypt( $this->mcrypt_rijndael, $key, $data, $this->mcrypt_mode, $init_vect ), "\0" );
    }

    private function add_cipher_noise( $data, $key )
    {
        $keyhash = md5( $key );
        $keylen = strlen( $keyhash );
        $str = '';

        for ($i = 0, $j = 0, $len = strlen( $data ); $i < $len; ++$i, ++$j)
        {
            if ($j >= $keylen)
            {
                $j = 0;
            }

            $str .= chr( (ord( $data[$i] ) + ord( $keyhash[$j] )) % 256 );
        }

        return $str;
    }

    private function remove_cipher_noise( $data, $key )
    {
        $keyhash = md5( $key );
        $keylen = strlen( $keyhash );
        $str = '';

        for ($i = 0, $j = 0, $len = strlen( $data ); $i < $len; ++$i, ++$j)
        {
            if ($j >= $keylen)
            {
                $j = 0;
            }

            $temp = ord( $data[$i] ) - ord( $keyhash[$j] );

            if ($temp < 0)
            {
                $temp = $temp + 256;
            }

            $str .= chr( $temp );
        }
        return $str;
    }

}

