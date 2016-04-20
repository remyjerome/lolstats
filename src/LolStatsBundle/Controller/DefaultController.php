<?php

namespace LolStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('LolStatsBundle:Home:index.html.twig');
    }
    public function summonerAction($name)
    {
        $name="faycebouque";
        var_dump($name2);
        //Choose your server
        $server ='euw';
        $curl = curl_init();
        curl_setopt_array($curl, array(
         CURLOPT_RETURNTRANSFER => 1,
         CURLOPT_URL => 'https://euw.api.pvp.net/api/lol/'.$server.'/v1.4/summoner/by-name/'.$name.'?api_key=3a658fd8-36df-4bb3-a498-51c9ff075b0f'
    ));
    if(!curl_exec($curl)){
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    }
    $result = curl_exec($curl);
    $phpObj =  json_decode($result);
    //var_dump($phpObj);
    $id = $phpObj->{$name}->{'id'};

    $curl1 = curl_init();
    curl_setopt_array($curl1, array(
       CURLOPT_RETURNTRANSFER => 1,
       CURLOPT_URL => 'https://euw.api.pvp.net/api/lol/'.$server.'/v1.3/stats/by-summoner/'.$id.'/ranked?season=SEASON2016&api_key=3a658fd8-36df-4bb3-a498-51c9ff075b0f'
    ));
    if(!curl_exec($curl1)){
        die('Error: "' . curl_error($curl1) . '" - Code: ' . curl_errno($curl1));
    }
    $result1 = curl_exec($curl1);
    $phpObj1 =  json_decode($result1);
    //var_dump($phpObj1);
    $stats = $phpObj1->{'champions'};
    //var_dump($stats);
    foreach ($stats as $champion)
    {
        if($champion->{'id'} == 0)
        {
            //var_dump($champion->{'stats'});
            $champion = $champion->{'stats'};
        }
    }
    echo "Win                       ".$champion->{'totalSessionsWon'}."\r";
echo "Lost                      ".$champion->{'totalSessionsLost'}."\r";
echo "WinRate                ".round(($champion->{'totalSessionsWon'}/$champion->{'totalSessionsPlayed'})*100,2)."\r";
echo "K/D/A                  ".$champion->{'totalChampionKills'}."/".$champion->{'totalDeathsPerSession'}."/".$champion->{'totalAssists'}."\r";
echo "Double                 ".$champion->{'totalDoubleKills'}."\r";
echo "Triple                   ".$champion->{'totalTripleKills'}."\r";
echo "Quadra                 ".$champion->{'totalQuadraKills'}."\r";
echo "Penta                    ".$champion->{'totalPentaKills'}."\r";
    return $this->render(
      'LolStatsBundle:Summoner:index.html.twig'
    );
    //,array('id'  => $id)
    }
}
