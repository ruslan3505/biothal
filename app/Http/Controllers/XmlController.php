<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers\XmlTo1c;


class XmlController extends Controller
{

    public function getXml()
    {
        $myObj = new XmlTo1c();
        $nbv = $myObj->xml();
//        dd($nbv);
    }

    public function xmlto1c()
    {
        $myObj = new XmlTo1c();
        $nbv = $myObj->xmlCurl();
//        dd($nbv);
    }
}
