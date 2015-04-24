<?php
/*
** unzipdata
**
** unzips data from Meteoswiss inside the folder meteoswiss
**
*/

/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * @param boolean $deleteRootToo Delete specified top-level directory as well
 *
 * Change: don't delete .htaccess and php files
 */
function unlinkRecursive($dir, $deleteRootToo)
{
    if(!$dh = @opendir($dir))
    {
        return;
    }
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..' || $obj == '.htaccess' || (stripos(strrev($obj), strrev('.php')) === 0))
        {
            continue;
        }

        if (!@unlink($dir . '/' . $obj))
        {
            unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);

    if ($deleteRootToo)
    {
        @rmdir($dir);
    }

    return;
}

function zip_extract($file, $extractPath) {

    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        echo "unzipping: $file";
        return TRUE;
    } else {
    	echo "fauled unzipping $file";
        return FALSE;
    }

} // end function



ignore_user_abort(1); // run script in background
set_time_limit(0); // run script forever 


$folder = 'meteoswiss/';
$extractto = 'data';

// 09.03.2015: Don't delete the content of the folder anymore...
unlinkRecursive($extractto, false);

echo "Unzipping folder: $folder <br>";

foreach (glob($folder . '*.zip') as $filename) {
    zip_extract($filename, $extractto);
}



// Compress PNGS 
$filematch = '*.png';

function optimize_png($file) {
    $pngquality = 9;

    $originalsize = filesize($file);

    $image = imagecreatefrompng($file);
    imagealphablending($image, true);
    imagesavealpha($image, true);


    ob_start();
    $success = @imagePNG($image);
    $tmpsize = ob_get_length();
    $image_string = ob_get_clean(); 

    echo "old file size: $originalsize <br>";
    echo "new file size: $tmpsize <br>";

    if($tmpsize < $originalsize) {
        imagepng($image,$file,$pngquality);
        echo "optmized file $file <br><br>";
    } else {
        echo "No optmizisation possible on file $file <br><br>";
    }
    
    imagedestroy($image);
    return true;
}

foreach (glob($extractto . '/' . $filematch) as $filename) {
    echo "optmizing " . $filename . "<br>";
    optimize_png($filename);

}

echo "DONE";

