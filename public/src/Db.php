<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db
 *
 * @author David
 */
class Db extends SQLite3{
    
    public function createTables()
    {
        $sql =" 
            CREATE TABLE tweet(
            text           TEXT    NOT NULL,
            time           INT     NOT NULL,
            lat            INT     NOT NULL,
            lon            INT     NOT NULL,
            twit_id        TEXT    NOT NULL
        );";
             
        $this->exec($sql);
    }
    
    /**
     * @return type
     */
    public function getTimeRange()
    {
        $sql = "SELECT max(time) as maxTime, min(time) as minTime from tweet";
        $ret = $this->query($sql);
       $arr =  $ret->fetchArray();
        return  array(
            'maxTime' => $arr['maxTime'],
            'minTime' => $arr['minTime'],
        );        
    }
    
    /**
     * 
     * @param string $time
     * @return array
     */
    public function getTweets($time,$interval)
    {
        $sql = "SELECT * from tweet where time between ($time -$interval) and  $time";

        $ret = $this->query($sql);
        $tweet = $ret->fetchArray();
        $arrResults = array();
        
        while ($tweet){
           $arrResults[] = array(
               'text' => $tweet['text'],
               'lat' => $tweet['lat'],
               'lon' => $tweet['lon'],
               'time' => $tweet['time'],
               'twit_id' => $tweet['twit_id']
           ); 
           $tweet = $ret->fetchArray();
        }        
        return $arrResults; 
    }
    
    /**
     * 
     * @param string $tweet
     */
    public function saveTweet($tweet)
    {
        $text = $this->escapeString($tweet['text']);
        $sql = "INSERT INTO tweet (text,lat,lon,time,twit_id)
      VALUES ( '{$text}', '{$tweet['lat']}', '{$tweet['lon']}', '{$tweet['time']}','{$tweet['twit_id']}');";
      
      $this->exec($sql);
    }
}
