<?php
if ($this->params['controller'] != 'users' || ($this->params['controller'] == 'users' && $this->params['action'] != 'login')) {
    $this->element('check_access');
    $str = '';
    if (!empty($menu)) {
        $tmp = '';
        foreach ($menu as $index => $menuItem) {
            $classDir = 'dir';
            $icon = isset($menuItem['icon']) ? '<i class="menu-icon '.$menuItem['icon'].'"></i>' : '';
            if (empty($menuItem['submenu'])) {
                $classDir = '';
            }
            $tmp.='<li>';
            if ($menuItem['url'] != '') {
                $url = explode("/", $menuItem['url']);
                for ($i = 0; $i < sizeof($url); $i++) {
                    if ($url[$i] != '') {
                        $urlController = $url[$i];
                        $urlView = $url[$i + 1];
                        break;
                    }
                }
                if (checkAccess($user['User']['id'], $urlController, $urlView)) {
                    $linkContent = $icon . '<span class="menu-text">'.__($menuItem['text'], true).'</span>';
                    $tmp.=$html->link($linkContent, '/' . $menuItem['url'], array(
                        'class' => 'menu-item ' . $classDir . ' ' . $menuItem['target'], 
                        'escape' => false
                    ));
                }
            } else {
                $tmp.=$this->Html->tag('span', $icon . '<span class="dir-text">'.$menuItem['text'].'</span>', array('class' => $classDir));
            }
            if (!empty($menuItem['submenu'])) {
                $subTmp = '';
                foreach ($menuItem['submenu'] as $subMenu) {
                    $classDir = 'dir';
                    $subIcon = isset($subMenu['icon']) ? '<i class="menu-icon '.$subMenu['icon'].'"></i>' : '';
                    if (empty($subMenu['submenu'])) {
                        $classDir = '';
                    }
                    $subTmp.='<li>';
                    if ($subMenu['url'] != '') {
                        $url = explode("/", $subMenu['url']);
                        for ($i = 0; $i < sizeof($url); $i++) {
                            if ($url[$i] != '') {
                                $urlController = $url[$i];
                                $urlView = $url[$i + 1];
                                break;
                            }
                        }
                        if (checkAccess($user['User']['id'], $urlController, $urlView)) {
                            $linkContent = $subIcon . '<span class="menu-text">'.__($subMenu['text'], true).'</span>';
                            $subTmp.=$html->link($linkContent, '/' . $subMenu['url'], array(
                                'class' => 'menu-item ' . $classDir . ' ' . $subMenu['target'], 
                                'escape' => false
                            ));
                        }
                    } else {
                        $subTmp.=$this->Html->tag('span', $subIcon . '<span class="dir-text">'.$subMenu['text'].'</span>', array('class' => $classDir));
                    }
                    if (!empty($subMenu['submenu'])) {
                        $subSubTmp = '';
                        foreach ($subMenu['submenu'] as $subSubMenu) {
                            $subSubIcon = isset($subSubMenu['icon']) ? '<i class="menu-icon '.$subSubMenu['icon'].'"></i>' : '';
                            $url = explode("/", $subSubMenu['url']);
                            for ($i = 0; $i < sizeof($url); $i++) {
                                if ($url[$i] != '') {
                                    $urlController = $url[$i];
                                    $urlView = $url[$i + 1];
                                    break;
                                }
                            }
                            if (checkAccess($user['User']['id'], $urlController, $urlView)) {
                                $linkContent = $subSubIcon . '<span class="menu-text">'.__($subSubMenu['text'], true).'</span>';
                                $subSubTmp.='<li>' . $html->link($linkContent, '/' . $subSubMenu['url'], array(
                                    'class' => 'menu-item ' . $subSubMenu['target'], 
                                    'escape' => false
                                )) . '</li>';
                            }
                        }
                        if ($subSubTmp != '') {
                            $subTmp.='<ul class="submenu">' . $subSubTmp . '</ul>';
                        }
                    }
                    $subTmp.='</li>';
                }
                if (str_replace(array("<li>", "</li>"), "", $subTmp) != '') {
                    $tmp.='<ul class="submenu">' . $subTmp . '</ul>';
                }
            }
            $tmp.='</li>';
        }
    }
    if (str_replace(array("<li>", "</li>"), "", $tmp) != '') {
        $str = '<ul id="nav" class="menu-list">' . $tmp . '</ul>';
    }
}
// echo "<pre>".htmlentities($str)."</pre>"; 
echo $str; 
?>

<script type="text/javascript">
    $(document).ready(function () {
        // Clean up empty menu items
        // $("#nav").find("li:has(span):not(:has(li))").remove();
        // $(".dir").parent(":not(:has(ul))").find(".dir").removeAttr("class");
        // $("#nav").find("li:has(span):has(li)").each(function(){
        //     if($(this).html().replace(/<li><\/li>/g,"").indexOf("<li>")==-1){
        //         $(this).remove();
        //     }
        // });
        // $("#nav").find("li").each(function(){
        //     if($(this).text() == ""){
        //         $(this).remove();
        //     }
        // });
        
        // Add tooltips for collapsed menu
        // $(".menu-container.collapsed .menu-item, .menu-container.collapsed .dir").hover(
        //     function() {
        //         $(this).find(".tooltip").show();
        //     },
        //     function() {
        //         $(this).find(".tooltip").hide();
        //     }
        // );
    });
</script>