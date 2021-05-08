<?php

require_once("ApiFetcher.php");

class Synhronize{

    public function start(){

        $fetcher = new ApiFetcher();
        $limit = 500;
        $offset = 0;
        
        while(true){
            $res = $fetcher->getContactsWithoutDeals($limit, $offset);
            if ($res === null) {
            break;
            }
            $offset+=500;
            }
        
        

    }


}