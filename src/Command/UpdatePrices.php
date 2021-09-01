<?php

namespace Snowdog\Academy\Command;

use Exception;
use Snowdog\Academy\Core\Migration;
use Snowdog\Academy\Model\CryptocurrencyManager;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePrices
{
    private CryptocurrencyManager $cryptocurrencyManager;

    public function __construct(CryptocurrencyManager $cryptocurrencyManager)
    {
        $this->cryptocurrencyManager = $cryptocurrencyManager;
    }

    public function __invoke(OutputInterface $output)
    {
        // TODO
        // use $this->cryptocurrencyManager->updatePrice() method

        $http_response=file_get_contents('https://api.coincap.io/v2/rates');
        $json=json_decode($http_response);
        if($json===null){
            printf("ERROR in JSON! (possible network error)\n");
            return;
        }

        $items=[];
        foreach($json->data as $item) $items[$item->id]=$item->rateUsd;
     
        $currencies=[
        'bitcoin',
        'bitcoin-cash',
        //'cardano', //missing
        'dash',
        'ethereum',
        //'ethereum-classic', //missing
        'litecoin',
        //'polkadot', //missing
        //'stellar', //missing
        //'tron', //missing
        ];

        foreach($currencies as $id){
            $price=@$items[$id];
            if($price===null){
                printf("%s: not found!\n",$id);
                continue;
            }
            echo $id," ",$price,"\n";
            $this->cryptocurrencyManager->updatePrice($id,$price);
        }
        printf("Update done.\n");
    }
}
