`<?php

class ApiFetcher{
    
    public function __construct(){

        $subdomain = 'mihailudintsev'; 
        $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token';

       
        $data = [
            'client_id' => '60156ff9-a791-41e0-ba02-072cf5f0c66e',
            'client_secret' => 'sBOhKtWnQSlXCGiMXLPcF5yQ4m93cA5H1CvESQ6M2QfPIQKqzh8cZhlhiDfMV0Lk',
            'grant_type' => 'authorization_code',
            'code'=> "def50200f7a0c58754064f698da00aa96ae2d64fcf53ac13d487975b1322a0396ac4da3074ddb19bb91f5d491937554347a9fbdc49c68a28212ebd08366c537220ca7a6257fe5a7d97111b83e6640fcdb0b26de334d5ac3c468494ef54c383eb481cd6155c795ea568186ca45dabcaf676489dfb3db01156e6503585c3199b731a5b64369aba8ad7048bb35b4f44e6c1ef76ab8bcd9d16bca9730d693522a29a36ee653f4d54f5f8187da3145a9a94b51fe98ebb5c49380a8502586bbcd593933c291d5a04286cc42724451336c690c137d5fb9df86f9b0143db35d47e41340dcc5311d45875b8532773cf97509093684cde861f51b8488688c17c7fcc735b2dd02e7f87b298d41fb576003fab8ea49d50c9dc3fa7299079922ceab50c2833071a2b959c8bfea587ea9b49eb548b29d1d25d6205d161ec6bf957b042e6596a0a88153a5ede4aad092a03a51e23ccf9e996eb49b32b5b1aae5752bf03593967e0bf5237728ebbbed4e04ba96736d320313756f0682b670d4b6f5dfff4551bc47f44d975ba706197489a555bd341790e7a003a06d1f3e6a4f3e252ff37fc0b3db15f663ca5b2f62398d87117bc2b1cd57bfed4374fe19c833d483f296eee",
            'redirect_uri' => 'https://testtest.ru',
        ];
      
        $curl = curl_init(); 
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); 

        
        curl_close($curl);

        
        $response = json_decode($out, true);

        $this->access_token = $response['access_token']; 

        //echo $this->access_token;
        
        
    }

    public function addTask($id){

        $subdomain = 'mihailudintsev';

        $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/tasks';

        $data = [
            'add' =>[
                [
                'element_id' => $id,
                'element_type' => 1,
                'text' => 'Контакт без сделок',
                'created_at'=> date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                ]
            ]
            ];
        var_dump($data);
        
        $curl = curl_init(); 
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Authorization: Bearer ' . $this->access_token]);
        curl_setopt($curl,CURLOPT_POST, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        
        
        $out = curl_exec($curl); 
        curl_close($curl);

        $response = json_decode($out, true);


    }

    public function getContactsWithoutDeals($limit, $offset){
        
        $subdomain = 'mihailudintsev'; 
        $link = 'https://' . $subdomain . '.amocrm.ru/api/v2/contacts?limit_rows='.$limit.'&limit_offset='.$offset.'&entity=contacts';

        $curl = curl_init($link); 
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $this->access_token]);
        
        $out = curl_exec($curl); 

        curl_close($curl);
        $response = json_decode($out, true);
        if($response==null){return;}
        for($i=0;$i<count($response["_embedded"]["items"]);$i++){

            if(empty($response["_embedded"]["items"][$i]["leads"])){

                $this->addTask($response["_embedded"]["items"][$i]["id"]);

            }else{
                continue;
            };
    }
        
        return $response;
    }

    
}
