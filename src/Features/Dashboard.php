<?php
/** 
 * @package DWContentPilot
 */
namespace DW\ContentPilot\Features;

use DW\ContentPilot\Core\{ Store };
use DW\ContentPilot\Lib\{ WPPage };

class Dashboard extends WPPage {

    private $store;
    private $parent;
    private $load_flag = True;

    function __construct( $__FILE__ = 'DWContentPilot' ) {

        $this -> parent = new Home();

        $this -> store = new Store();
        $this -> store -> log( get_class($this).':__construct()', '{STARTED}' );

        parent::__construct();

        $class_name = explode('\\', get_class($this));
        $class_name = array_pop($class_name);

        $_result = $this -> addSubPage (array(
            'parent_slug' => $this -> parent -> get_slug(),
            'page_title' => $class_name, 
            'menu_title' => $class_name, 
            'capability' => 'manage_options', 
            'menu_slug' => DWContetPilotPrefix . '_' . get_class($this)
        ));

        if(!$_result) {

            $this -> load_flag = false;
            return $this -> store -> debug( get_class($this).':__construct()', '{FAILED}' );

        }
    
    }

    public function register() {

        if(!$this -> load_flag) return false;
            
        $this -> store -> log( get_class($this).':register()', '{STARTED}' );

        $this -> parent -> register();

        add_action(DWContetPilotPrefix.'register_actions', array( $this, 'register_actions'));
    }

    public function register_actions() {

        if(!$this -> load_flag) return false;

        add_action(DWContetPilotPrefix.'register_menus', array($this, 'register_page'));
        
    }

}

