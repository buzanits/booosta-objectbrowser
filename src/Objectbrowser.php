<?php
namespace booosta\objectbrowser;

use \booosta\Framework as b;
b::init_module('objectbrowser');


class Objectbrowser extends \booosta\base\Module
{
  use moduletrait_objectbrowser;

  protected $used_objects = [];

  
  public function __construct(protected Object $obj) {}

  public function get($path = '', $obj = null)
  {
    if($obj === null) $obj = $this->obj;
    if($path === '') return $obj;

    $chunks = explode('/', $path);
    $chunk0 = array_shift($chunks);
    $pathx = implode('/', $chunks);

    if(is_numeric($chunk0) && is_array($obj)) return $this->get($pathx, $obj[$chunk0]);
    if(is_object($chunk0)) return $this->get($pathx, $obj->{$chunk0});

    return null;  // something went wrong
  }

  public function set($data, $path = '', &$obj = null)
  {
    if($obj === null) $obj = $this->obj;
    $chunks = explode('/', $path);
    $chunk0 = array_shift($chunks);
    
    if(sizeof($chunks) == 0):
      $obj = $data;
    elseif(sizeof($chunks) == 1):
      if(is_numeric($chunks0) && is_array($obj)) $obj[$chunk0] = $data;
      elseif(is_object($chunk0)) $obj->{$chunk0} = $data;;
    else:
      $pathx = implode('/', $chunks);
      if(is_numeric($chunk0) && is_array($obj)) $this->set($data, $pathx, $obj[$chunk0]);
      if(is_object($chunk0)) $this->set($data, $pathx, $obj->{$chunk0});;
    endif;
  }

  public function get_html($obj = null)
  {
    #b::debug($obj);
    if($obj === null) $obj = $this->obj;
    
    if(is_array($obj)):
      $size = max(sizeof($obj), 1);
      $html = "<table class='objectbrowser_table'><tr class='objectbrowser_tr'>
               <td class='objectbrowser_td' rowspan='$size'><b>Array</b></td>";
      $first = true;

      foreach($obj as $idx => $data):
        if($first):
          $tr = "";
          $first = false;
        else:
          $tr = "<tr class='objectbrowser_tr'>";
        endif;

        $data_html = $this->get_html($data ?? '');
        $html .= "$tr<td class='objectbrowser_td'>$idx</td><td class='objectbrowser_td'>$data_html</td></tr>";
      endforeach;

      $html .= "</table>";
    elseif(is_object($obj)):
      #b::debug('object'); b::debug($obj); b::debug(spl_object_id($obj));
      if(in_array(spl_object_id($obj), $this->used_objects)) return "**Recursion**";

      $this->used_objects[] = spl_object_id($obj);

      $class = get_class($obj);
      if($class == '__PHP_Incomplete_Class' || !class_exists($class)) return "**Undef**";
      #b::debug("class $class exists");

      if(is_callable([$obj, 'get_vars'])) $vars = $obj->get_vars();
      else $vars = get_object_vars($obj);
      #b::debug($vars);

      $size = max(sizeof($vars), 1);

      $html = "<table class='objectbrowser_table'><tr class='objectbrowser_tr'>
               <td class='objectbrowser_td' rowspan='$size'><b>Object</b><br>($class)</td>";
      $first = true;

      foreach($vars as $idx => $data):
        if($first):
          $tr = "";
          $first = false;
        else:
          $tr = "<tr class='objectbrowser_tr'>";
        endif;

        $data_html = $this->get_html($data ?? '');
        $html .= "$tr<td class='objectbrowser_td'>$idx</td><td class='objectbrowser_td'>$data_html</td></tr>";
      endforeach;
  
      $html .= "</table>";
    else:
      #$html = "<pre>$obj</pre>";
      $html = $obj;
    endif;

    #b::debug($this->used_objects);
    return $html;
  }
}

