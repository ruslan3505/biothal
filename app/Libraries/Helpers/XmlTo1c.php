<?php


namespace App\Libraries\Helpers;

use App\Models\Admin\Products\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use SimpleXMLElement;


class XmlTo1c
{
    public function xml()
    {
        $data = Product::all();
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        $this->array_to_xml($data, $xml_data);
        $result = $xml_data->asXML('name.xml');
//        dd($result);

    }

    public function array_to_xml($data, &$xml_data)
    {
        if (!empty($xml_data)) {
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {
                    $key = 'item' . $key; //dealing with <0/>..<n/> issues
                }
                if (is_array($value)) {
                    $subnode = $xml_data->addChild($key);
                    $this->array_to_xml($value, $subnode);
                } else {
                    $xml_data->addChild("$key", htmlspecialchars("$value"));
                }
            }
        }
    }

    public function xmlCurl()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.github.com/repos/guzzle/guzzle');
        echo $response->getStatusCode(); // 200
        echo $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
        echo $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'

    }

}
