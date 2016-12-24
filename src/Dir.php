<?php

class Dir
{
    /**
     * directory color
     **/
    private static $dir = 'red';

    /**
     * file color
     **/
    private static $file = 'red';

    /**
     * recursion active memory
     **/
    private static $buff = null;

    /**
     * toggle for first div class assignment
     **/
    private static $classed = false;

    /**
     * toggle for style dump
     **/
    private static $styled = false;

    /**
     * holds highlight rules
     **/
    private static $highlighted = array();


    /**
     * Dumps a css style(prevents duplicate of style)
     *
     * @return string
     */
    private static function style()
    {
        if ( ! static::$styled)
        {
            static::$styled = true;
            return '
            <style>
                img {height: 2%;margin-right: 5px;}
                div{border-left: 1px dotted #000;position: relative;top: -4px;padding-left: 10px;padding-top: 10px;font-size: 12px;}
                div:before{content: \'\';height: 500px;width: 11px;position: absolute;margin-top: 8px;margin-left: -11px;border-top: 1px dotted #000;}
                div:last-child:before{background: #fff;}
                .clsd, .clsd:before{border: none;background: transparent;}
            </style>
            ';
        }
        return '';
    }


    /**
     * gets all directories and files inside a given path
     *
     * @param string $path
     * @return array
     */
    private static function globed($path)
    {
        return array_diff(scandir($path), array('.', '..'));
    }


    /**
     * highlight a specific file or directory
     *
     * @param string $dir
     * @param string $path
     * @return string
     */
    private static function checkHighlight($dir, $path)
    {
        if (array_key_exists($dir, static::$highlighted))
        {
            return static::$highlighted[$dir];
        }
        elseif (array_key_exists($path, static::$highlighted))
        {
            return static::$highlighted[$path];
        }
        return '0';
    }

    /**
     * evaluates that and build structure based on path
     *
     * @param string $path
     * @param int $margin
     * @return string
     */
    private static function evaluate($path, $margin = 0)
    {
        $DS = DIRECTORY_SEPARATOR;
        foreach (static::globed($path) as $dirs)
        {
            $class = '';
            if ( ! self::$classed)
            {
                $class = ' class="clsd"';
                self::$classed = true;
            }

            $styled = '';
            $color = static::checkHighlight($dirs, $path . $DS . $dirs);
            if ( $color != '0')
            {
                $styled = ' style="color:%s;"';
            }

            if (is_dir($path . $DS . $dirs))
            {
                $color = ( ! is_null($color)) ? $color : self::$dir;
                $styled = sprintf($styled, $color);
                if (count(static::globed($path . $DS . $dirs)) < 1)
                {
                    $format = '<div style="margin-left:%spx;"%s><img src="src/Assets/D_%sE.png"> <span%s>%s</span>';
                    self::$buff .= sprintf($format, $margin, $class, self::$dir, $styled, $dirs);
                }
                else
                {
                    $format = '<div style="margin-left:%spx;"%s><img src="src/Assets/D_%s.png"> <span%s>%s</span>';
                    self::$buff .= sprintf($format, $margin, $class, self::$dir, $styled, $dirs);
                    self::evaluate($path . $DS . $dirs, 10);
                }
                self::$buff .= '</div>';
            }
            else
            {
                $color = ( ! is_null($color)) ? $color : self::$file;
                $styled = sprintf($styled, $color);
                $format = '<div style="margin-left:%spx;"%s><img src="src/Assets/F_%s.png"> <span%s>%s</span></div>';
                self::$buff .= sprintf($format, $margin, $class, self::$file, $styled, $dirs);
            }

        }

        return self::$buff;
    }


    /**
     * Builds folder and files structure
     *
     * @param  string $path
     * @return string
     */
    public static function structure($path)
    {
        $buffer = static::evaluate($path);
        self::$buff = null;
        static::$classed = false;
        return self::style() . $buffer;

    }


    /**
     * Sets color of directory and file icon.
     *
     * @param string|null $directory
     * @param string|null $file
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


    /**
     * makes highlight rules
     *
     * @param string $name
     * @param string|null $color
     * @return void
     */
    public static function highlight($name, $color = null)
    {
        static::$highlighted[$name] = $color;
    }
}
