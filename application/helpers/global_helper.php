<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Define Global Values
$DEV_ENV = (ENVIRONMENT === 'development');

define('API_URL',      $DEV_ENV ? 'http://localhost:8080/' : 'http://api.fixdrepair.com/');
define('API_SITE_DIR', $DEV_ENV ? 'fxapi.dev' : 'api.fixdrepair.com');
define('API_UPLOAD_ROOT', '../../' . API_SITE_DIR . '/public/images/');

unset($DEV_ENV);

/**
 * @replace: $file_url = base_url().$field_info->extras->upload_path.'/'.$value;
 * @with: $file_url = api_image_url($field_info->extras->upload_path, $value);
 * @in: application\libraries\Grocery_CRUD.php
 * @on_line_num: 2666
 * Set image path on page load
 */
function crud_image_path_fix($upload_path, $image_name){
    return API_URL .'images/'. $image_name;
}


function starts_with($haystack, $needle){
    return strpos($haystack, $needle) === 0;
}

function ends_with($haystack, $needle){
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

function menu_active($class)
{
    $url = uri_string();
    
    if ($url === $class || (!empty($class) and starts_with($url, $class)))
        return 'active';
    else
        return '';
}

/**
 * @param 2D Array $menu
 * @return Void
 */ 
function build_menu(Array $menus)
{
    foreach ($menus as $menu): 
        list ($title, $path, $icon) = $menu; ?>

        <li class="<?= menu_active( $path ) ?>">
            <a href="<?= site_url( $path ) ?>">
                <i class="<?= $icon ? $icon : 'fa fa-table'?>"></i> <?= $title ?>
            </a>
        </li>
    
    <?php endforeach; 
}

/**
 * @param String $title
 * @param String $icon
 * @param 2D Array $child
 * @return Void
 */ 
function build_dropdown_menu($title, $icon, Array $child)
{
    $val = array_filter(array_map(function($arr){ return menu_active($arr[1]); }, $child));

    ?>
    <li class="dropdown <?= count($val) ? 'active' : '' ?>">
        <a href="javascript:;">
            <i class="<?= $icon ?>"></i> <?= $title ?> <span class="caret"></span>
        </a>

        <ul class="sub-nav">
            <?php build_menu($child) ?>
        </ul>
    </li>
    <?php
}