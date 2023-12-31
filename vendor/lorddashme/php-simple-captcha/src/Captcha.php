<?php

/*
 * This file is part of the Simple Captcha.
 *
 * (c) Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LordDashMe\SimpleCaptcha;

use LordDashMe\SimpleCaptcha\Utility\HexToRGB;

/**
 * Captcha Class.
 * 
 * A simple captcha package that suite to any type of web application built on php.
 * 
 * @author Joshua Clifford Reyes <reyesjoshuaclifford@gmail.com>
 */
class Captcha
{
    /**
     * The default config for the simple captcha.
     * This can be override by passing an array of config
     * in the initialization process of the class.
     * 
     * @return array
     */
    protected $config = array(
        'session_name'       => 'ldm-simple-captcha',
        'session_index_name' => 'LDM_SIMPLE_CAPTCHA',
        'session_https'      => false,
        'session_http_only'  => true,
        'font_color'         => '#000',
        'font_size_min'      => 26,
        'font_size_max'      => 28,
        'angle_min'          => 0,
        'angle_max'          => 9,
        'shadow'             => true,
        'shadow_color'       => '#fff',
        'shadow_offset_x'    => -3,
        'shadow_offset_y'    => 1,
        'backgrounds' => array(
            'bg1.png',
            'bg2.png',
            'bg3.png',
            'bg4.png',
            'bg5.png',
            'bg6.png',
            'bg7.png',
            'bg8.png'
        ),
        'fonts' => array(
            'capsmall_clean.ttf'
        )
    );

    /**
     * The captcha generated unique code.
     * 
     * @return string 
     */
    protected $code = '';

    /**
     * The captcha generated image code.
     * 
     * @return string
     */
    protected $image = '';

    /**
     * The class constructor.
     * 
     * @param  array  $config    Override the default config of the captcha when
     *                           the class initialized.
     * 
     * @return void
     */
    public function __construct($config = array()) 
    {
        $this->init($config);
    }

    /**
     * The sub method for the class constructor.
     * 
     * @param  array  $config    To override the default config of the captcha.
     * 
     * @return void
     */
    public function init($config = array())
    {
        $this->config = \array_merge($this->config, $this->configRestriction($config));
    }

    /**
     * The configuration restriction, this will reset the input config into
     * default values to avoid any conflicts in the process later on.
     * 
     * @param  array  $config    The unfiltered input config.
     * 
     * @return array
     */
    protected function configRestriction($config)
    {
        return $this->configAngleRestriction(
            $this->configFontSizeRestriction($config)
        );
    }

    /**
     * Config restriction for the angle setup. This restrict important values
     * and restore to default value if detected a violation.
     * 
     * @param  array  $config
     * 
     * @return array
     */
    protected function configAngleRestriction($config)
    {
        if(isset($config['angle_min']) && $config['angle_min'] < 0) {
            $config['angle_min'] = 0;
        }

        if(isset($config['angle_max']) && $config['angle_max'] > 10) {
            $config['angle_max'] = 10;
        }

        if (isset($config['angle_max']) && isset($config['angle_min'])) {
            if ($config['angle_max'] < $config['angle_min']) {
                $config['angle_max'] = $config['angle_min'];
            }
        }

        return $config;
    }

    /**
     * Config restriction for the font size setup. This restrict important values
     * and restore to default value if detected a violation.
     * 
     * @param  array  $config
     * 
     * @return array
     */
    protected function configFontSizeRestriction($config)
    {
        if(isset($config['font_size_min']) && $config['font_size_min'] < 10) {
            $config['font_size_min'] = 10;
        }

        if (isset($config['font_size_max']) && isset($config['font_size_min'])) {
            if ($config['font_size_max'] < $config['font_size_min']) {
                $config['font_size_max'] = $config['font_size_min'];
            }
        }

        return $config;
    }

    /**
     * The code generated by the generate unique code method base
     * on the given length. The code generated will be pass to the
     * code property that will be use to print an image of captcha code.
     * 
     * @param  int  $length    The given length for the captcha code.
     * 
     * @return $this
     */
    public function code($length = 5)
    {
        // When the length value is lower than
        // the minimum which is 5 then we must enforce to
        // use the default value of 5 to avoid short code.
        if ($length < 5) {
            $length = 5;
        }

        $this->code = $this->generateUniqueCode($length);

        return $this;
    }

    /**
     * The getter method for the code property class.
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Generate unique code base on the given length and
     * the allowed code characters.
     * 
     * @param  int  $length    The code max length to be generate.
     * 
     * @return string
     */
    protected function generateUniqueCode($length)
    {
        $characters = $this->allowedCodeCharacters();
        $charactersLength = (\strlen($characters) - 1);
        
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $number = \rand(0, $charactersLength);
            $jumbleNumber = \rand(0, $number);
            $code .= $characters[$jumbleNumber];
        }
        
        return $code;   
    }

    /**
     * The allowed code characters that can be generated
     * by the generated unique code method.
     * 
     * @return string
     */
    protected function allowedCodeCharacters()
    {
        return 'ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789';
    }

    /**
     * The trigger for the generate image base on the current
     * property code values. This will generate an base64 data image
     * that can be use to output in the front facing.
     * 
     * @return $this
     */
    public function image()
    {
        $this->image = $this->generateBase64Image();
    }

    /**
     * The getter method for the image property class.
     * 
     * @return string
     */
    public function getImage()
    {
        return $this->image;    
    }

    /**
     * The generate process of the base64 image. This will be the captcha
     * image, the content of the image will be base on the code generated.
     * Mainly used the default config property of the class to provide the default
     * setup of the captcha image.
     * 
     * @return string
     */
    protected function generateBase64Image()
    {
        // Create the canvas of the image and providing
        // the size base on the background image picked randomly
        // in the list of the config.
        $background     = $this->backgrounds();
        $backgroundSize = $this->backgroundSize($background);
        $imageCanvas    = $this->imageCanvas($background);
        
        // Prepare all the setup for the text content. The text
        // content will be base on the generated code string.
        // The position of the text content will be determine 
        // base on the random min and max setup.
        $fontColor      = $this->fontColor($imageCanvas);
        $textAngle      = $this->textAngle();
        $font           = $this->fonts();
        $fontSize       = $this->fontSize();
        $textBoxSize    = $this->textBoxSize($textAngle, $font, $fontSize, $this->getCode());
        $textPosition   = $this->textPosition($textBoxSize, $backgroundSize);  

        // Generating the actual text content and combining with the created
        // canvas base on the selected background image. This is the heavy part
        // of the code image processing.
        $imageCanvas = $this->drawShadow(
            $imageCanvas, $textAngle, $font, $fontSize, $textPosition, $this->getCode()
        );      

        $imageCanvas = $this->drawText(
            $imageCanvas, $textAngle, $font, $fontSize, $textPosition, $fontColor, $this->getCode()
        );

        $imageCanvas = $this->drawTextCrossLine(
            $imageCanvas, $backgroundSize, $textAngle, $textPosition
        );
        
        // Output the generated image using output buffer of PHP and
        // convert the image value to base64 that will be concat to image data url header.
        // This process is very nasty because of the linear execution in the memory, the
        // string value of the base64 will depends on the generated image. To make sure
        // a free space of memory in one single request we must unset the unused variable(s).
        $image = $this->imageExportContents($imageCanvas);
        
        unset($imageCanvas);

        $data = 'data:image/png;base64,';
        $data .= \base64_encode($image);

        unset($image);

        return $data;
    }

    /**
     * Prepare the background that will be use in the image canvas.
     * This return the path of the background images.
     * 
     * @return string
     */
    protected function backgrounds()
    {
        $index = \mt_rand(0, \count($this->config['backgrounds']) -1);

        return $this->backgroundsDirectoryPath() . $this->config['backgrounds'][$index];
    }

    /**
     * Base on the prepared background image, we must determine the
     * dimension of that image.
     * 
     * @param  string  $background
     * 
     * @return array
     */
    protected function backgroundSize($background)
    {
        list($bgWidth, $bgHeight, $bgType, $bgAttr) = \getimagesize($background);

        return array(
            'bg_width' => $bgWidth,
            'bg_height' => $bgHeight,
            'bg_type' => $bgType,
            'bg_attr' => $bgAttr
        );
    }

    /**
     * Create the image canvas base on the given background image.
     * The canvas enforce the use of png image type only.
     * 
     * @param  string  $background
     * 
     * @return mixed
     */
    protected function imageCanvas($background)
    {
        return \imagecreatefrompng($background);
    }

    /**
     * Prepare the font color base on the config setup value.
     * The value generated will be pass on the image canvas.
     * 
     * @param  mixed  $imageCanvas
     * 
     * @return mixed
     */
    protected function fontColor($imageCanvas)
    {
        $rgb = $this->convertHexToRGB($this->config['font_color']);

        return \imagecolorallocate($imageCanvas, $rgb['r'], $rgb['g'], $rgb['b']);
    }

    /**
     * Prepare the text angle that will be use of text content 
     * in the image canvas.
     * 
     * @return int
     */
    protected function textAngle()
    {
        $textAngleRandom = \mt_rand(
            $this->config['angle_min'], $this->config['angle_max']
        );

        return $textAngleRandom * (\mt_rand(0, 1) == 1 ? -1 : 1);
    }

    /**
     * Prepare the font style base on the list of config setup.
     * This return the path of the font styles.
     * 
     * @return string
     */
    protected function fonts()
    {
        $index = \mt_rand(0, \count($this->config['fonts']) - 1);

        return $this->fontsDirectoryPath() . $this->config['fonts'][$index];
    }


    /**
     * Prepare the font size that will be use in the created image canvas.
     * The value will be randomly picked base on the given range min and max.
     * 
     * @return int
     */
    protected function fontSize()
    {
        return \mt_rand($this->config['font_size_min'], $this->config['font_size_max']);
    }

    /**
     * Prepare the text box size that will be use of the text content.
     * 
     * @param  int     $textAngle
     * @param  string  $font
     * @param  int     $fontSize
     * @param  string  $code
     * 
     * @return mixed
     */
    protected function textBoxSize($textAngle, $font, $fontSize, $code)
    {
        return \imagettfbbox($fontSize, $textAngle, $font, $code);   
    }

    /**
     * The random computation of the text position of the provided code content.
     * 
     * @param  mixed  $textBoxSize
     * @param  array  $backgroundSize
     * 
     * @return array
     */
    protected function textPosition($textBoxSize, $backgroundSize)
    {
        $boxWidth = \abs($textBoxSize[6] - $textBoxSize[2]);
        $boxHeight = \abs($textBoxSize[5] - $textBoxSize[1]);

        $textPositionXMin = 0;
        $textPositionXMax = $backgroundSize['bg_width'] - $boxWidth;
        
        if ($textPositionXMin > $textPositionXMax) {
            $textPositionXMax = $textPositionXMin;
        }

        $textPositionX = \mt_rand($textPositionXMin, $textPositionXMax);

        $textPositionYMin = $boxHeight;
        $textPositionYMax = ($backgroundSize['bg_height'] - ($boxHeight / 2) - 5);

        if ($textPositionYMin > $textPositionYMax) {
            $temp_textPositionY = $textPositionYMin;
            $textPositionYMin = $textPositionYMax;
            $textPositionYMax = $temp_textPositionY;
        }

        $textPositionY = \mt_rand($textPositionYMin, $textPositionYMax);

        if ($textPositionX < 30 && $textPositionY < 40) {
            return array(
                'text_position_x' => 30,
                'text_position_y' => 45
            );
        }
        
        return array(
            'text_position_x' => $textPositionX,
            'text_position_y' => $textPositionY
        );
    }
    
    /**
     * The draw process in the image canvas generated before.
     * This requires the final setup of image canvas, also the draw shadow
     * will be executed base on the config setup if allowed or not.
     * 
     * @param  mixed   $imageCanvas
     * @param  int     $textAngle
     * @param  string  $font
     * @param  int     $fontSize
     * @param  array   $textPosition
     * @param  string  $code
     * 
     * @return $imageCanvas
     */
    protected function drawShadow($imageCanvas, $textAngle, $font, $fontSize, $textPosition, $code)
    {
        if (! $this->config['shadow']) {
            return $imageCanvas;   
        }

        $shadowColor = $this->convertHexToRGB($this->config['shadow_color']);
            
        $shadowColor = \imagecolorallocate(
            $imageCanvas, 
            $shadowColor['r'], $shadowColor['g'], $shadowColor['b']
        );
        
        \imagettftext(
            $imageCanvas, $fontSize, $textAngle, 
            $textPosition['text_position_x'] + $this->config['shadow_offset_x'], 
            $textPosition['text_position_y'] + $this->config['shadow_offset_y'], 
            $shadowColor, $font, $code
        );

        return $imageCanvas;
    }

    /**
     * The draw process of the text content base on the code provided.
     * This method is like the print process that will provide the content in the created
     * image canvas.
     * 
     * @param  mixed   $imageCanvas
     * @param  int     $textAngle
     * @param  string  $font
     * @param  int     $fontSize
     * @param  array   $textPosition
     * @param  mixed   $fontColor
     * @param  string  $code
     * 
     * @return $imageCanvas
     */
    protected function drawText($imageCanvas, $textAngle, $font, $fontSize, $textPosition, $fontColor, $code)
    {
        \imagettftext(
            $imageCanvas, $fontSize, $textAngle, 
            $textPosition['text_position_x'], 
            $textPosition['text_position_y'], 
            $fontColor, $font, $code
        );

        return $imageCanvas;
    }

    /**
     * The draw process for the text cross line. This process will add more complexity
     * for the reading automation of the generated image captcha.
     * 
     * The process of adding more challenge to any automation attempt in the captcha image.
     * 
     * @param  mixed  $imageCanvas
     * @param  array  $backgroundSize
     * @param  array  $textPosition
     * 
     * @return $imageCanvas
     */
    protected function drawTextCrossLine($imageCanvas, $backgroundSize, $textAngle, $textPosition)
    {
        $text_position_y = $textPosition['text_position_y'];

        $lineAngle = $textAngle > 0 ? (($text_position_y) - $textAngle * 5) : (($text_position_y));

        $black = imagecolorallocate($imageCanvas, 20, 20, 20);
        imagesetthickness($imageCanvas, 4);
        imageline($imageCanvas, 10, $text_position_y - 14, $backgroundSize['bg_width'] - 10, $lineAngle, $black);

        return $imageCanvas;
    }

    /**
     * The background resources base directory path.
     * 
     * @return string
     */
    protected function backgroundsDirectoryPath()
    {
        return dirname(__FILE__) . '/../resources/backgrounds/';
    }

    /**
     * The font resources base directory path.
     * 
     * @return string
     */
    protected function fontsDirectoryPath()
    {
        return dirname(__FILE__) . '/../resources/fonts/';
    }

    /**
     * The wrapper for the hex to rgb class convert method.
     * This essential to wrapped in a method that add more 
     * flexibility to change later on.
     * 
     * @param  string  $hexString
     * 
     * @return array
     */
    protected function convertHexToRGB($hexString)
    {
        return HexToRGB::convert($hexString);
    }

    /**
     * The export functions for the image depending on the selected
     * image type.
     * 
     * @param  mixed   $image    The generated image content.
     *
     * @return string 
     */
    protected function imageExportContents($image)
    {
        \ob_start();

        \imagepng($image);

        return \ob_get_clean();
    }

    /**
     * The store session method that add the ability to transfer the generated
     * code in the other places, for example pages etc.
     * This later on will be the basis of the validation of user organic inputed
     * code base on the image perspective.
     * 
     * @return void
     */
    public function storeSession()
    {
        $this->startSession();
        
        $_SESSION[$this->config['session_index_name']] = array(
            'code' => $this->getCode()
        );
    }

    /**
     * The get session method way to collect the stored session data.
     * This can be use for validation of organic code provided by the user.
     * 
     * @return array
     */
    public function getSession()
    {
        if (! isset($_COOKIE[$this->config['session_name']])) {
            return false;
        }

        $this->startSession();

        $data = $this->collectSessionData();
        
        \session_unset();
        \session_destroy();

        unset($_COOKIE[$this->config['session_index_name']]);

        return $data;
    }

    /**
     * The start session method, initialize the session for captcha instance.
     * The max session time is set to 15mins and the garbage collector can now truncate this
     * unused session. Some of the security setup are provided in the config property like
     * https and http only, the other depends on the PHP ini setup.
     * 
     * @return void
     */
    protected function startSession()
    {
        $cookie = \session_get_cookie_params();
        
        \session_set_cookie_params( $cookie['lifetime'], $cookie['path'], $cookie['domain'], 
            $this->config['session_https'], $this->config['session_http_only']
        );

        \session_name($this->config['session_name']);
        \session_start(array('gc_maxlifetime' => 860));
    }

    /**
     * The collector for the session data. This check first if the session is the index of
     * captcha in the session available if not then return a null value.
     * 
     * @return mixed
     */
    protected function collectSessionData()
    {
        if (! isset($_SESSION[$this->config['session_index_name']])) {
            return null;
        }

        return $_SESSION[$this->config['session_index_name']];
    }
}