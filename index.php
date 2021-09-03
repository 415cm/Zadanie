<?php
    //zalacznie parsera DOM HTML
    include_once('simple_html_dom.php');


    //dane serwera oraz login do bazy danych
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "artykuly";
    $conn = new mysqli ($servername, $username, $password, $dbname);
    global $conn;


    //przekazanie do zmiennej zawartosci plikow XML
    $rmf=file_get_contents("https://www.rmf24.pl/sport/feed");
    $xmoon=file_get_contents("http://xmoon.pl/rss/rss.xml");
    $ks=file_get_contents("https://www.komputerswiat.pl/.feed");
    
    $feedrmf= new SimpleXMLElement($rmf);
    $feedxmoon= new SimpleXMLElement($xmoon);
    $feedks = new SimpleXMLElement($ks);

    //petle zapisujace artykuly w bazie
    $i=0;
    foreach($feedxmoon->entry as $entry){
        $sql = "INSERT IGNORE INTO `articles` (`Title`,`PubDate`,`Content`,`AddDate`) VALUES 
        ('$entry->title','$entry->published','$entry->content','$entry->updated')";
        $result = $conn->query($sql);
        if(++$i > 5) break;
    }

    $j=0;
    foreach($feedrmf->channel->item as $entry){
        /*
        $html = file_get_html($entry->link);
        $ret = $html->find('div.article-date', 0);
        var_dump($ret);
       */
       $sql = "INSERT IGNORE INTO `articles` (`Title`,`PubDate`,`Content`,`AddDate`) VALUES 
        ('$entry->title','$entry->pubDate','$entry->description','$entry->pubDate')";
        $result = $conn->query($sql);
        if(++$j >= 5) break;
    }

    $k=0;
    foreach($feedks->entry as $entry){
        $sql = "INSERT IGNORE INTO `articles` (`Title`,`PubDate`,`Content`,`AddDate`) VALUES 
        ('$entry->title','$entry->published','$entry->summary','$entry->updated')";
        $result = $conn->query($sql);
        if(++$k > 5) break;
    }
?>