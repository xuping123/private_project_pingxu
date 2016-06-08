<?php
/**
 *  QueryList使用示例
 *  
 * 入门教程:http://doc.querylist.cc/site/index/doc/4
 * 
 * QueryList::Query(采集的目标页面,采集规则[,区域选择器][，输出编码][，输入编码][，是否移除头部])
* //采集规则
* $rules = array(
*   '规则名' => array('jQuery选择器','要采集的属性'[,"标签过滤列表"][,"回调函数"]),
*   '规则名2' => array('jQuery选择器','要采集的属性'[,"标签过滤列表"][,"回调函数"]),
*    ..........
*    [,"callback"=>"全局回调函数"]
* );
 */
    ini_set('max_execution_time', '0');
//修改此次最大运行内存
    ini_set('memory_limit','1024M');
    header("Content-Type: text/html; charset=UTF-8");
    require 'phpQuery.php';
    require 'QueryList.php';
    require 'conn.php';
    use QL\QueryList;
    $marray=array(
            "http://www.dy2018.com/0/",  
            "http://www.dy2018.com/1/",  
            "http://www.dy2018.com/2/",  
            "http://www.dy2018.com/3/",  
            "http://www.dy2018.com/4/",  
            "http://www.dy2018.com/5/",  
            "http://www.dy2018.com/6/",  
            "http://www.dy2018.com/7/",  
            "http://www.dy2018.com/8/",  
            "http://www.dy2018.com/9/",  
            "http://www.dy2018.com/10/",  
            "http://www.dy2018.com/11/",  
            "http://www.dy2018.com/12/",  
            "http://www.dy2018.com/13/",  
            "http://www.dy2018.com/14/",  
            "http://www.dy2018.com/15/",  
            "http://www.dy2018.com/16/",  
            "http://www.dy2018.com/17/",  
            "http://www.dy2018.com/18/",  
            "http://www.dy2018.com/19/",  
            "http://www.dy2018.com/20/",
            "http://www.dy2018.com/html/tv/hytv/",  
            "http://www.dy2018.com/html/tv/hepai/",  
            "http://www.dy2018.com/html/tv/gangtai/",  
            "http://www.dy2018.com/html/tv/oumeitv/",  
            "http://www.dy2018.com/html/tv/rihantv/",  
            "http://www.dy2018.com/html/tv/tvzz/",  
            ); 


    for ($i=0; $i <count($marray); $i++) { 
        

    $html = iconv('GBK','UTF-8',file_get_contents($marray[$i]));
    // 获取总页面 和第一页的电影的地址
    $data = QueryList::Query($html,array(
       'link' => array('b>a','href'),
       'name' =>array('b>a','html'),
       'page' =>array('.x','html'),
        ))->data;



    foreach ($data as $key => $value) {
         $str=substr($value['link'],0,3);
         if($str=='/i/'){
            $array[]=$value['link'];
         }
         if(!empty($value['page'])){
            $page=mb_substr($value['page'],10,3,'UTF-8');
         }
    }


     for ($j=2; $j <$page ; $j++) { 
        // echo $marray[$i].'/index_'.$j.'.html'.'<br>';
        // 提前请求一次， 转码编码问题， 已经完整拼接好 电影路径 
        // $html = iconv('GBK','UTF-8',file_get_contents($marray[$i].'/index_'.$j.'.html'));
        // 开始对每个页面请求， 分析电影名称和下载地址
        $temp_array = QueryList::Query($marray[$i].'/index_'.$j.'.html',array(
       'link' => array('b>a','href'),
       'name' =>array('b>a','html'),
        ))->data;
        //只获取 左边电影， array最终电影数组， 获取后立马释放，
        foreach ($temp_array as $k => $v) {
         $str=substr($v['link'],0,3);
         if($str=='/i/'){
            $array[]=$v['link'];
         }
      }
    }



    // unset($data);
    // unset($temp_array);
    //拼接字符串 组成电影实际url
    $count=count($array);
    for($ii=0;$ii<$count;$ii++){
        $movie_url='http://www.dy2018.com'.$array[$ii];
           
        $html = iconv('GBK','UTF-8',file_get_contents($movie_url));
        $result = QueryList::Query($html,array(
       'down_url' => array('table>tbody>tr>td>a','href'),
       'movie_name'=>array('table>tbody>tr>td>a','html'),
       ))->data;

        // $down_url=$result['down_url'];
        // $movie_name=$result['movie_name'];
        foreach ($result as $key => $value) {
            $down_url=$value['down_url'];
            $movie_name=$value['movie_name'];
            mysql_query("insert into dy2018_url (url, movie_name,status) values('$down_url','$movie_name',2)"); 
        }
        // 
     }

 }

   // print_r($result);



