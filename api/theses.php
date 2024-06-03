<?php

class Theses
{
    
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getTheses($pracovisko, $typ_prace)
    {
        $url_theses = 'https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste='.$pracovisko;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_theses);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);

        $dom = new DOMDocument();
        $dom->loadHTML($content);
        $xpath = new DOMXPath($dom);

        $rows = $xpath->query("//tr[contains(@class, 'uis-hl-table')]");
        $theses = [];
        foreach ($rows as $row) {
            $tds = $row->getElementsByTagName('td');
            $typ = $tds->item(1)->nodeValue;
            $nazov = $tds->item(2)->nodeValue;
            $ucitel = $tds->item(3)->nodeValue;
            $pracovisko = $tds->item(4)->nodeValue;
            $program = $tds->item(5)->nodeValue;

            $link = '';

            if($tds->length == 10) {
                    $aElement = $tds->item(7)->getElementsByTagName('a')->item(0);
                if ($aElement) {
                    $link = $aElement->getAttribute('href');
                }
            }
            else{
                $aElement = $tds->item(8)->getElementsByTagName('a')->item(0);
                if ($aElement) {
                    $link = $aElement->getAttribute('href');
                }
            }


            $obsadenost = $tds->item($tds->length - 2)->nodeValue;

            $prihlaseno = explode('/', trim($obsadenost))[0];
            $max = explode('/', trim($obsadenost))[1];

            if($prihlaseno == $max || $typ != $typ_prace) {
                continue;
            }

            $abstract = '';

            if ($link) {
                $url = 'https://is.stuba.sk' . $link; 
                $html = file_get_contents($url); 
                $doc = new DOMDocument();
                $doc->loadHTML($html); 
                $tables = $doc->getElementsByTagName('table');
                $lastTable = $tables->item(0); 
                $trs = $lastTable->getElementsByTagName('tr');
                $lastTr = $trs->item($trs->length - 1); 
                $tdsInLastTr = $lastTr->getElementsByTagName('td');
                $abstract = $tdsInLastTr->item($tdsInLastTr->length - 1)->nodeValue;
            }


            $theses[] = [
                'typ' => $typ,
                'nazov' => $nazov,
                'ucitel' => $ucitel,
                'pracovisko' => $pracovisko,
                'program' => $program,
                'obsadenost' => $obsadenost,
                'abstract' => $abstract
            ];
        }

        return json_encode(['status' => 'success', 'data' => $theses]);
    }

}
?>