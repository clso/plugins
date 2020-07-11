<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 *
 * 随机图片挂件widget
 *
 * @package Typecho-RandomThumbnail
 * @author  LittleJake
 * @version 1.0.0
 * @link https://blog.littlejake.net
 */
class RandomThumbnail_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法
     *
     * @return void
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->beforeRender = array(
            'RandomThumbnail_Plugin',
        );

    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @access public
     * @return void
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){
        $url = new Typecho_Widget_Helper_Form_Element_Textarea('url', NULL, NULL, _t('缩略图地址'), _t('输入图片地址，一行一条'));
        $lazyload = new Typecho_Widget_Helper_Form_Element_Radio('lazyload',array('0' => _t('否'),'1' => _t('是')),'0', _t('开启Lazyload'));
        $lazyload_tag = new Typecho_Widget_Helper_Form_Element_Text('lazyload_tag', NULL, NULL, _t('图片URL标签'), _t('输入图片图片URL标签'));
        $lazyload_url = new Typecho_Widget_Helper_Form_Element_Text('lazyload_url', NULL, NULL, _t('替换图片地址'), _t('输入图片地址'));

        $form->addInput($url);
        $form->addInput($lazyload);
        $form->addInput($lazyload_tag);
        $form->addInput($lazyload_url);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     *
     * 获取缩略图
     *
     * @author LittleJake
     * @param int $seed 随机数
     * @param string $height 图片高度
     * @param string $width 图片宽度
     * @param string $class 自定义class
     * @param string $style 自定义样式
     * @return bool
     */

    public static function getThumbnail($seed = 0,$height = "fit-content", $width = "fit-content", $class = '', $style = '')
    {
        try{
            $url = Typecho_Widget::widget('Widget_Options')->plugin('RandomThumbnail')->url;
            $lazyload = Typecho_Widget::widget('Widget_Options')->plugin('RandomThumbnail')->lazyload;
            $lazyload_tag = Typecho_Widget::widget('Widget_Options')->plugin('RandomThumbnail')->lazyload_tag;
            $lazyload_url = Typecho_Widget::widget('Widget_Options')->plugin('RandomThumbnail')->lazyload_url;

            $urls = explode("\r\n",$url);

            if(sizeof($urls) == 0)
                return false;

            $seed = $seed>0?$seed:rand(0,9999);
            $num = sizeof($urls);

            $index = $seed % $num;

            if($lazyload == 1)
                echo "<div style='width: $width; height: $height; overflow: hidden; border-radius: 10px; max-height: 100%; max-width: 100%'>
<img $lazyload_tag='$urls[$index]' src='$lazyload_url' alt='head-img-$seed' class='$class' style='$style'>
</div>";
            else
                echo "<div style='width: $width; height: $height; overflow: hidden; border-radius: 10px; max-height: 100%; max-width: 100%'>
<img src='$urls[$index]' alt='head-img-$seed' class='$class' style='$style'>
</div>";
            return true;
        } catch (\Exception $e){
            return false;
        }

    }
}
