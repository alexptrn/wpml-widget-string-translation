<?php
/**
 * Plugin Name: WPML Widget string translation
 * Plugin URI: #
 * Description: This plugin allows to add widget translations, integrating with WPML String Translation
 * Version: 1.0.0
 * Author: Alessandro Paterno
 * Author URI: #
 * License: GPL
 */

if (!class_exists('WpmlWidgetStringTranslation')) {

    class WpmlWidgetStringTranslation
    {
        public function __construct()
        {
            add_filter('widget_update_callback', array($this, 'saveWidgetStrings'), 10, 4);
            add_filter('widget_display_callback', array($this, 'translateWidgetStrings'), 10, 3);
        }

        public function saveWidgetStrings($instance, $new_instance, $old_instance, $widgetObj)
        {
            $widgetId = $widgetObj->id;
            $widgetName = $widgetId;
            $sidebarId = $this->getSidebarId($widgetId);
            $stringContext = $sidebarId;

            foreach ($instance as $fieldName => $fieldValue) {
                $stringName = $widgetName . ' - ' . $fieldName;
                if (function_exists('icl_register_string')) {
                    icl_register_string($stringContext, $stringName, $fieldValue);
                }
            }

            return $instance;
        }

        public function translateWidgetStrings($instance, $widgetObj, $args)
        {
            $widgetId = $widgetObj->id;
            $widgetName = $widgetId;
            $sidebarId = $this->getSidebarId($widgetId);
            $stringContext = $sidebarId;

            foreach ($instance as $fieldName => $fieldValue) {
                $stringName = $widgetName . ' - ' . $fieldName;
                if (function_exists('icl_t')) {
                    $instance[$fieldName] = icl_t($stringContext, $stringName, $fieldValue);
                }
            }

            return $instance;
        }

        public function getSidebarName($wid = null)
        {
            global $wp_registered_sidebars;

            $sidebarId = $this->getSidebarId($wid);

            if (isset($wp_registered_sidebars[$sidebarId])) {
                return $wp_registered_sidebars[$sidebarId]['name'];
            }

            return false;
        }

        public function getSidebarId($wid = null)
        {
            $sidebars = wp_get_sidebars_widgets();

            foreach ($sidebars as $sidebarId => $sidebarWidgets) {
                foreach ($sidebarWidgets as $widgetId) {
                    if ($widgetId == $wid) {
                        return $sidebarId;
                    }
                }
            }

            return false;
        }

    }

    $WpmlWidgetStringTranslation = new WpmlWidgetStringTranslation();
}
