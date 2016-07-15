<?php

class DirStruct
{
    //sort's arrays (folder then files)
    private static function sortFiles($path)
    {
        $directory = glob($path . '*', GLOB_ONLYDIR);
        $contents = glob($path . '*');
        return array_unique(array_merge($directory, $contents));
    }
    
    public static function structure($path, $is_loop = false)
    {
        $html = null;
        $pull_down = $margin = '';
         //make sure we have a trailing slash
        $path = rtrim($path, '/') . '/';
        if (is_dir($path)) {
            
            $contents = self::sortFiles($path);
            $directory = trim($path, '/');
            
            if ($is_loop) {
                $margin = 29;
                $pull_down = 'padding-top:5px;margin-top:-5px;';
            }
            
            if (!$is_loop) {
                $html .= '<div style="padding-left:35px;">';
                $html .= '<div style="margin-left:-25px;margin-top:5px;">';
                $html .= '<span>&#x0229F;</span><span> &#128194;</span>';
                $html .= '<span style="font-size:11px;"> ' . $directory . '</span><br />';
                $html .= '</div>';
            }
            
            foreach ($contents as $files => $FOD) {
                
                if (is_dir($FOD)) {
                    $FOD = str_replace($path, '', $FOD);    
                    $html .= '<div style="margin-left:' . $margin . 'px;' . $pull_down .'';
                    $html .= 'border-left:1px dotted #000;">';
                    $html .= '<div style="margin-left:-7px;"><span>&#x0229F;</span>';
                    $html .= '<span>&#x02500; </span><span> &#128194;</span>';
                    $html .= '<span style="font-size:11px;"> ' . $FOD . '</span><br />';
                    $html .= '</div>';
					//get structure of current directory
                    $html .= self::structure($path . $FOD, true);
                    $html .= '</div>';
                }
                else {
                    $FOD = str_replace($path, '', $FOD);
                    if ($is_loop == true) {
                        $html .= '<div style="margin-left:' . $margin . 'px;';
                        $html .= 'border-left:1px solid #000;margin-top:-3px;">';
                        $html .= '<span>&#x02500; </span><span> 🃏 </span>';
                        $html .= '<span style="font-size:11px;"> ' . $FOD . '</span><br />';
                        $html .= '</div>';
                    } else {
                        $html .= '<div style="border-left:1px solid #000;">';
                        $html .= '<span>&#x02500; </span><span> 🃏 </span>';
                        $html .= '<span style="font-size:11px;"> ' . $FOD . '</span><br />';
                        $html .= '</div>';
                    }
                }
            }
            return $html;
        }
        else {
            throw new Exception($path . ' is not a directory');
        }
    }
}