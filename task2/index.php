<?php
$string = 'https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3';
 
$changed_string = function($string){
    $url = parse_url($string);
 
    parse_str($url['query'],$params);
 
    while(true){
        $searchParams = array_search('3',$params);
 
        if($searchParams){
            unset($params[$searchParams]);
        }
        else{
            break;
        }
    }
 
    asort($params);
 
    $params['url'] = $url['path'];
    $get = http_build_query($params);
 
    $string = explode('?',$string,2);
 
    $string[1] = $get;
 
    return implode('?',$string);
};
 
$string = $changed_string($string);
echo $string;