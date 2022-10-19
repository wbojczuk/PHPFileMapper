<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Mapper</title>
    <!-- ---------- START OF CSS STYLING ---------- -->
    <style>
        *{
    margin:0;
    padding:0;
}

body{
    min-width: 100vw;
    
}

.fm-container{
    display: inline-block;
    margin: 2vh 0 2vh 8vw;
    width: auto;
    min-width: 100vw;
    max-width: 300vw;
    
    position: relative;
    white-space: nowrap;

}

.fm-item{
    border: 0px solid transparent;
    border-radius: 5px;
    position: relative;
    display: inline-flex;
    min-width: 5vw;
    min-height: 3vw;
    padding: 4px;
    align-items: center;
    justify-content: center;
    color: black;
    text-decoration: none;
    margin: 5px;
}

.fm-dir-root{
    border: 0px solid transparent;
    border-radius: 5px;
    position: absolute;
    display: inline-flex;
    min-width: 5vw;
    min-height: 3vw;
    padding: 4px;
    align-items: center;
    justify-content: center;
    top:1vw;
    left:1vw;
    margin: 5px;
    font-weight: 800;
    background-color: rgb(253, 228, 194);
}
/* COLORS */
.fm-dir{
    background-color: rgb(253, 228, 194);
}
.fm-file{
    background-color: #dfdfdf;
}

.fm-webfile{
    background-color: #dd4b25;
}

.fm-cssfile{
    background-color: #0070bb;
}

.fm-phpfile{
    background-color: #7377ad;
}

.fm-rasterfile{
    background-color: #24bb9c;
}

.fm-vectorfile{
    background-color: #f69022;
}

.fm-jsfile{
    background-color: #dab92d;
}

.fm-textfile{
    background-color: #9aceff;
}

.fm-compressfile{
    background-color: #cc9acd;
}

.fm-top-level{
    margin-top: 2vh;
}

.fm-top-level:after{
    content: '';
    display: inline-block;
    position: absolute;
    bottom: -4vh;
    left: 1vw;
    width: 2px;
    height: 4vh;
    background-color: #aaa;
}
.fm-top-level-last:after{
    display: none;
}
.fm-lower-level{
    margin-left: 1vw;
    top: 1.5vh;
}
.fm-lower-level:after{
    content: '';
    display: inline-block;
    position: absolute;
    left: -2vw;
    top: 3px;
    width: 2vw;
    height: 2px;
    background-color: #ccc;
    z-index: -10;
}
.fm-dir-root:after{
    content: '';
    display: inline-block;
    position: absolute;
    right: -2vw;
    top: calc(50% - 1px);
    width: 2vw;
    height: 3px;
    background-color: #aaa;
    z-index: -10;
}

.fm-lower-wrapper{
    display: inline-block;
    position: relative;
}
.fm-lower-after:after{
    content: '';
    display: inline-block;
    position: absolute;
    top: 0;
    left:-1vw;
    min-width: 100%;
    height: 2px;
    margin-top: 12px;
    padding: 0 2vw;
    background-color: #ccc;
    z-index: -10;
}
    </style>
<!-- ---------- END OF CSS STYLING ---------- -->


</head>
<body>
<div class="fm-dir-root">ROOT</div>
    <div class="fm-container">

        <!-- ---------- START OF PHP SCRIPTING ---------- -->
    <?php

    // --- DIRECTORY TO SEACH IN ---
    // IF DIRECTORY IS SET OUT OF THE WEB SERVER, LINKS WILL BE BROKEN
    $directory = "./";

    // --- FOLDERS TO IGNORE (CASE INSENSITIVE) ---
    $ignore_folders = array(
        "git"
    );

    // ---------- END OF MANAGEABLE CODE ----------



    // SET UP REGEX TO TEST FOR IGNORE FOLDERS

    $ignore_RegEx = "";
    if(count($ignore_folders) > 0){
        $ignore_RegEx = "/";
    for($i = 0; $i < count($ignore_folders); ++$i){
        if($i < (count($ignore_folders) - 1)){
            $ignore_RegEx .= "({$ignore_folders[$i]})|";
        }else{
            $ignore_RegEx .= "({$ignore_folders[$i]})";
        }
    }
    $ignore_RegEx .= "/i";
}

    $items = scandir($directory);
    $current_lvl = 0;
    $incrementer = 0;

    array_shift($items);
    array_shift($items);
    
    // Ignore Folders
    if(count($ignore_folders) > 0){$regloop = count($items); for($i = 0; $i < $regloop; ++$i){
        if((is_dir($directory . "/" . $items[$i])) && (preg_match($ignore_RegEx, $items[$i], $matches))){
            array_splice($items, $i, 1);
            $regloop =  count($items);
            $i = 0;
        }
    }}
    

    // Loop Through Items In Root Directory
    check_files($items, $directory);

    function check_files(&$items, $directory){

            for($o = 0; $o < count($items); ++$o){

                $current_item = $items[$o];
                $is_last = ($o==(count($items) - 1));
               check_file($current_item, $directory, $is_last);
    
        }
        
    }

    // Check Root Files
    function check_file($current_item, $directory, $is_last){
        $current_item_path = $directory . "/" . $current_item;
       
        if(is_file($current_item_path)){
            
            if(preg_match("/(\.html)|(\.xml)/i", $current_item_path , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-webfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.css)/i", $current_item_path , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-cssfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.php)/i", $current_item_path , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-phpfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.png)|(\.jpg)|(\.jpeg)|(\.bmp)|(\.gif)|(\.tiff)/i", $current_item , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-rasterfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.ai)|(\.eps)|(\.svg)|(\.pdf)|(\.psd)|(\.gimp)/i", $current_item , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-vectorfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.doc)|(\.docx)|(\.odt)|(\.rtf)|(\.tex)|(\.txt)|(\.wpd)/i", $current_item , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-textfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.7z)|(\.zip)|(\.rar)|(\.pkg)|(\.deb)|(\.arj)|(\.exe)|(\.rpm)|(\.tar\.gz)|(\.z)/i", $current_item , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-compressfile fm-top-level'>$current_item</a><br>";
            }else if(preg_match("/(\.js)/i", $current_item_path , $matches)){
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-jsfile fm-top-level'>$current_item</a><br>";
            }else {
                $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-file fm-top-level'>$current_item</a><br>";
            }
            
           if($is_last){
            $tempstr = str_replace("class='","class='fm-top-level-last ", $tempstr);
           }
            echo $tempstr;
            

        }else if(is_dir($current_item_path)){

            $GLOBALS['current_lvl'] = 1;
            
            $tempstr = "<a target='_blank' href='$current_item_path' class='fm-item fm-dir fm-top-level'>$current_item</a>";
             
           if($is_last){
            $tempstr = str_replace("class='","class='fm-top-level-last ", $tempstr);
           }
            echo $tempstr;
            
                

                  if(count(scandir($current_item_path)) >= 3){
                    // 1 OR MORE SUBDIRS

                     subdirs($current_item_path);
                     
                  }else{
                    echo "<br>";
                    // NO SUBDIRS
                    
                  }
    }
}

// Move down and Test Sub-Directories
function subdirs($root_path){
    $local_lvl = $GLOBALS['current_lvl'];
    $top_amt = $local_lvl + -0.1;
    $temp_items =  scandir($root_path);
    array_shift($temp_items);
    array_shift($temp_items);

    if(count($GLOBALS["ignore_folders"]) > 0){$regloop =  count($temp_items); for($i = 0; $i < $regloop; ++$i){
        if((is_dir($root_path . "/" . $temp_items[$i])) && (preg_match($GLOBALS["ignore_RegEx"], $temp_items[$i], $matches))){
            array_splice($temp_items, $i, 1);
            $regloop =  count($temp_items);
            $i = 0;
        }
    }}

    for($i = 0; $i < count($temp_items); ++$i){
        $current_item = $root_path . "/" . $temp_items[$i];

        // IF FILE
        if(is_file($current_item)){
            if(preg_match("/(\.html)|(\.xml)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-webfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.css)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-cssfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.php)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-phpfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.png)|(\.jpg)|(\.jpeg)|(\.bmp)|(\.gif)|(\.tiff)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-rasterfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.ai)|(\.eps)|(\.svg)|(\.pdf)|(\.psd)|(\.gimp)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-vectorfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.doc)|(\.docx)|(\.odt)|(\.rtf)|(\.tex)|(\.txt)|(\.wpd)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-textfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.7z)|(\.zip)|(\.rar)|(\.pkg)|(\.deb)|(\.arj)|(\.exe)|(\.rpm)|(\.tar\.gz)|(\.z)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-compressfile' >{$temp_items[$i]}</a>";
            }else if(preg_match("/(\.js)/i", $temp_items[$i] , $matches)){
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-jsfile' >{$temp_items[$i]}</a>";
            }else {
                echo "<a target='_blank' href='$current_item' class='fm-item fm-lower-level fm-file' >{$temp_items[$i]}</a>";
            }
            
            
        } else if(is_dir($current_item)){
            // IS DIR
            
                echo "<a target='_blank' href='$current_item' class='fm-item fm-dir fm-lower-level' >{$temp_items[$i]}</a>";
            
            
            
            if((key_exists(($i + 1), $temp_items))){
           
               
            echo "<div class='fm-lower-wrapper fm-lower-after' style='top: {$top_amt}vh;'>";
            }else{
                echo "<div class='fm-lower-wrapper' style='top: {$top_amt}vh;'>";
            }
            subdirs($current_item);
            
            echo "</div>";
            
            
        }
        if(($i == (count($temp_items) - 1)) && $local_lvl == 1){
           echo "<br>";
        }
    }
    


}
    ?>
    </div>
</body>
</html>
