<?php

class Dir
{
    private static $dir = 'red';

    private static $file = 'red';

    private static $buff = null;

    private static $classed = false;


    /**
     *
     *
     * @return string
     */
    private static function style()
    {
        return '
        <style>
            img {height: 2%;margin-right: 5px;}
            div{border-left: 1px dotted #000;position: relative;top: -4px;padding-left: 10px;padding-top: 10px;font-size: 12px;}
            div:before{content: \'\';height: 15px;width: 11px;position: absolute;margin-top: 8px;margin-left: -11px;border-top: 1px dotted #000;}
            div:last-child:before{background: #fff;}
            .clsd, .clsd:before{border: none;background: transparent;}
        </style>
        ';
    }

    public static function structure($path, $margin = 0)
    {
        foreach (glob($path . '/*') as $counter => $dirs)
        {
            $name = explode('/', $dirs);
            $name = end($name);

            $class = '';
            if ( ! self::$classed)
            {
                $class = ' class="clsd"';
                self::$classed = true;
            }

            if (is_dir($dirs))
            {
                if (count(glob($dirs . '/*')) < 1)
                {
                    $format = '<div style="margin-left:%spx;"%s><img src="src/Assets/D_%sE.png"> %s';
                    self::$buff .= sprintf($format, $margin, $class, self::$dir, $name);
                }
                else
                {
                    $format = '<div style="margin-left:%spx;"%s><img src="src/Assets/D_%s.png"> %s';
                    self::$buff .= sprintf($format, $margin, $class, self::$dir, $name);
                    self::structure($dirs, 10);
                }
                self::$buff .= '</div>';
            }
            else
            {
                $format = '<div style="margin-left:%spx;"%s><img src="src/Assets/F_%s.png"> %s</div>';
                self::$buff .= sprintf($format, $margin, $class, self::$file, $name);
            }

        }

        return self::style() . self::$buff;
    }


    /**
     * Sets color of directory and file icon.
     * this depend if the icon already exists in Assets folder and have the below naming convention
     * <Type>_<Color><E|>.png
     *
     * @param null $directory
     * @param null $file
     * @return void
     */
    public static function setColor($directory = null, $file = null)
    {
        if ( ! is_null($directory))
        {
            self::$dir = $directory;
        }

        if ( ! is_null($file))
        {
            self::$file = $file;
        }

    }
}
