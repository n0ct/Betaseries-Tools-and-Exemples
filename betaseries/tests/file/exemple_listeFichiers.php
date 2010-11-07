<?php
/*
 * Created on 31 oct. 2010
 * Auteur: Benjamin Boulaud
 * Fichier: exemple_fichiers.php
 */
 
 echo "
 <br />
 <br />Vue serveur:<br /><br /><br/>

";
mkmap("/home/Donnees/Enermax/Series");
function mkmap($dir)
{
    echo "<ul>";   
    $folder = opendir ($dir);
    while ($file = readdir ($folder)) 
    {   
        if ($file != "." && $file != "..")
        {           
            $pathfile = $dir.'/'.$file;           
            echo "<li><a href=$pathfile>$file</a></li>";           
            if(filetype($pathfile) == 'dir')
            {               
                mkmap($pathfile);               
            }           
        }       
    }
    closedir ($folder);    
    echo "</ul>";   
}
?>
