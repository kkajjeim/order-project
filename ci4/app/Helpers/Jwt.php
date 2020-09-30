<?php
class Jwt
{
    protected $alg;
    function __construct()
    {
        $this->alg = 'sha256';
    }

    function encode(array $data)
    {
        $header = json_encode(array(
            'alg'=>$this->alg,
            'typ'=>'JWT'
        ));

        $payload = json_encode($data);

        $signature = hash($this->alg, $header.$payload);

        return base64_encode($header.'.'.$payload.'.'.$signature);
    }

    function decode($token)
    {
        $parted = explode('.', base64_decode($token));

        $signature = $parted[2];

        if(hash($this->alg, $parted[0].$parted[1]) == $signature)
            echo "\n\ngood\n\n";
        else
            exit("잘못된 signature 입니다");

        return json_decode($parted[1],true);
    }
}

