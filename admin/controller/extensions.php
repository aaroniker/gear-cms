<?php

class extensionsController extends controller {

    public function __construct() {

    }

    public function index($action = '', $plugin = '') {

        if(ajax::is()) {

            $plugin = type::post('plugin', 'string', $plugin);

            if($action == 'setActive') {
                if($plugin) {

                    $active = unserialize(option::get('plugins'));
                    $active = (!is_array($active)) ? [] : $active;

                    if(($key = array_search($plugin, $active)) !== false) {
                        unset($active[$key]);
                        message::success(lang::get('plugin_deactivated'));
                    } else {
                        $active[] = $plugin;
                        message::success(lang::get('plugin_activated'));
                    }

                    $active = (count($active)) ? serialize($active) : null;

                    option::set('plugins', $active);

                }
            } elseif($action == 'get') {
                ajax::addReturn(json_encode(plugin::getAll()));
            }

        }

        include(dir::view('extensions/plugins.php'));

    }

    public function blocks($action = '', $block = '') {

        if($action == 'show' && $block) {

            $block = new block($block.'.block');

            include(dir::view('extensions/blocks/show.php'));

        } else {

            if(ajax::is()) {

                $block = type::post('block', 'string', $block);

                if($action == 'setActive') {
                    if($block) {

                        $active = unserialize(option::get('blocks'));
                        $active = (!is_array($active)) ? [] : $active;

                        if(($key = array_search($block, $active)) !== false) {
                            unset($active[$key]);
                            message::success(lang::get('block_deactivated'));
                        } else {
                            $active[] = $block;
                            message::success(lang::get('block_activated'));
                        }

                        block::generateCSS($active);

                        $active = (count($active)) ? serialize($active) : null;

                        option::set('blocks', $active);

                    }
                } elseif($action == 'get') {
                    ajax::addReturn(json_encode(block::getAll()));
                }

            }

            include(dir::view('extensions/blocks/list.php'));

        }

    }

}

?>
