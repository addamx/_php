<?php

/*
递归创建目录树
 */

function createDirTree($dir, $lev)
{
    $fles = "";
    $Dirs = "";
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (false != ($file = readdir($dh))) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($dir . "/" . $file)) {
                        $fles .= "│" . str_repeat("&nbsp", $lev + 1) . $file . '</br>';
                    } else {
                        echo str_repeat("&nbsp", $lev) . "├─<b>" . $file . "</b></br>";
                        createDirTree($dir . "/" . $file, $lev + 1);
                    }

                }

            }
            echo $fles;
            closedir($dh);
        }
    }

}

createDirTree('Design', 0);
