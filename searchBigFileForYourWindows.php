<?php

class  a
{
    static $size = [];

    /**
     * @param $directory  查询文件夹
     * @param $c    递归次数
     * @param $postfix  指定后缀
     * @return array
     */
    public function digui($directory, $c = 1, $postfix)
    {
        $handle = @opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (@filetype($directory . '/' . $file) == 'dir') {
                        $this->digui($directory . '/' . $file, $c + 1, $postfix);
                    } elseif (is_file($directory . '/' . $file)) {

                        $extension = pathinfo($directory . '/' . $file, PATHINFO_EXTENSION);
                        if ($postfix) {
                            if ($postfix == $extension) {
                                self::$size[@filesize($directory . '/' . $file)] = $directory . '/' . $file;
                            }
                        } else {
                            self::$size[@filesize($directory . '/' . $file)] = $directory . '/' . $file;
                        }


                    }
                }

            }
        }

        return self::$size;
    }
}

//盘符
$output=[];
$postfix = null;
if(count($argv) == 3){
    $output[] = $argv[1];
    $postfix = $argv[2];
}else{
    // 执行wmic命令获取分区信息
    exec('wmic logicaldisk get name', $output);
    unset($output[count($output)-1]);
    unset($output[0]);

    $output = array_values($output);
}


$obj = new a();
$size =[];
foreach ($output as $k=>$v){
    $gbkString = iconv('UTF-8', 'GBK//IGNORE', '开始查询'.$v.'盘');
    var_dump($gbkString);
    $arr =$obj->digui($v.'/',1,$postfix);
    $gbkString = iconv('UTF-8', 'GBK//IGNORE', '结束查询'.$v.'盘');
    var_dump($gbkString);
    $size = $size+$arr;
}
krsort($size);
$gbkString = iconv('UTF-8', 'GBK//IGNORE', '占用空间最大的前10个文件');
var_dump($gbkString);
$count = 0;
foreach ($size as $key => $value) {
    if ($count < 20) {
        $gbkString = iconv('UTF-8', 'GBK//IGNORE', '占用'.($key/1024/1024)."MB");
        var_dump($value.$gbkString);
        $count++;
    } else {
        break; // 当打印了10个元素后，退出循环
    }
}


//执行  要求php7.0

//筛选所有盘 文件类型不限制
//命令行 php searchBigFileForYourWindows.php

//筛选D盘（盘可以换） 文件类型为zip(zip可以更换)
//命令行 php searchBigFileForYourWindows.php  D:  zip
//命令行 php searchBigFileForYourWindows.php  C:  zip

?>