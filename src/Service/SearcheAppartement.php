<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class SearcheAppartement
{

    public function available(Request  $request )
    {
        $checkIn= 0;
        $checkOut=0;
        if ($request->isMethod('POST'))
        {
            $checkIn= $request->query->get('checkIn');
            $checkOut=$request->query->get('checkOut');

        }
        return [$checkIn,$checkOut];

    }


}