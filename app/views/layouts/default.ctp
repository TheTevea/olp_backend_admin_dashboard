<?php
/**
 * Copyright UDAYA Technology Co,.LTD (http://www.udaya-tech.com)
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
include("includes/function.php");
$start = 2018;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <?php echo $this->element('embed_font'); ?>

        <title>
            <?php __('Olongpich Transport'); ?>
        </title>

        <!-- icon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->webroot; ?>img/favicon-1.ico?" />

        <!-- Style Sheet -->
        <!-- General Style Sheet -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/style.css?4334" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/table.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/button.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/filter_container.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/report.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/menu-slide-left.css?65" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/print.css" media="print" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></link>
        <!-- Jquery UI -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/jquery-ui-1.8.14.custom/development-bundle/themes/base/jquery.ui.all.css?9847" />
        <!-- Layout -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/jquery.layout.all-1.2.0/layout.css" />
        <!-- Menu -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/dropdown/dropdown.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/dropdown/themes/flickr.com/default.ultimate.css" />
        <!-- Validate -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/validateEngine/css/validationEngine.jquery.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/validateEngine/css/template.css" />
        <!-- Data Table -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/DataTables-1.8.1/media/css/custom.css?55665" />
        <!-- Choosen -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>js/harvesthq-chosen-v0.9.1/chosen_1.8.2/chosen.css?3234" />
        <!-- Tooltip -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/atooltip.css" />
        <!--  Auto Complete -->
        <link rel="stylesheet" type="text/css" href="<?php echo $this->webroot; ?>css/jquery.autocomplete.css" />
        <!-- JS Crop Photo -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/tapmodo-Jcrop-25f2e18/css/jquery.Jcrop.css" type="text/css" />
        <!-- Mini Select 
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/minimalect/jquery.minimalect.min.css" type="text/css" media="screen" />
        -->
        <!-- Check box Style -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/checkboxStyle/bootstrap2-toggle.css" />
        <!-- Tour -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/tours/introjs.css" />
        <!-- Time Picker -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/timePicker/jquery-ui-timepicker.css" />
        <!-- Scroll Bar -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/scrollbar/jquery.nicescroll.min.css" />
        <!-- Text Editor -->
        <link rel="stylesheet" href="<?php echo $this->webroot; ?>js/textEditor/jquery-te-1.4.0.css?324" />
        
        <!-- Jquery Script -->
        <!-- Jquery -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-1.7.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/shortcut.js"></script>
        <!-- Jquery UI -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-ui-1.8.14.custom/js/jquery-ui-1.8.14.custom.min-<?php echo $this->Session->read('lang'); ?>.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-ui-1.8.14.custom/js/ui.tabs.closable.min.js?313op"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery-ui-1.8.14.custom/js/ui.tabs.paging.js"></script>
        <!-- Layout -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.layout.all-1.2.0/jquery.layout.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.layout.all-1.2.0/jquery.layout.state.js"></script>
        <!-- Menu -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.dropdown.js"></script>
        <!-- Validator -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/validateEngine/js/jquery.validationEngine-<?php echo $this->Session->read('lang'); ?>.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/validateEngine/js/jquery.validationEngine.js"></script>
        <!-- Data Table -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/DataTables-1.8.1/media/js/jquery.dataTables.min.<?php echo $this->Session->read('lang'); ?>.js?kopi"></script>
        <!-- Choosen -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/harvesthq-chosen-v0.9.1/chosen_1.8.2/chosen.jquery.min.js"></script>
        <!-- autoNumeric -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/autoNumeric-1.6.2.js"></script>
        <!-- Price Format -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.price_format-1.3.js"></script>
        <!-- input mask for number - support unicode -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/uninums.min.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.caret.1.02.min.js"></script>
        <!-- Tooltip -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.atooltip.js"></script>
        <!-- Date -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/date-en-US.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/function.js"></script>
        <!-- Ajax Form -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.form.js"></script>
        <!--  Auto Complete -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.autocomplete.min.js"></script>
        <!-- JS Crop Photo -->
        <script src="<?php echo $this->webroot; ?>js/tapmodo-Jcrop-25f2e18/js/jquery.Jcrop.js" type="text/javascript"></script>
        <!-- List Box -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/listbox.js"></script>
        <!-- Format Currency -->
        <script type="text/javascript" src="<?php echo $this->webroot.'js/jquery.formatCurrency-1.4.0.min.js'; ?>"></script>
        <!-- To Word -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/toword/toword_<?php echo $this->Session->read('lang'); ?>.js"></script>
        <!-- High Chart -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/HighChart-4-2-2/js/highcharts.js"></script>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/HighChart-4-2-2/js/modules/exporting.js"></script>
        <!-- Check box Style -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/checkboxStyle/bootstrap2-toggle.min.js"></script>
        <!-- Menu Setting -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/menuSetting.js"></script>
        <!-- Tour -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/tours/intro.js"></script>
        <!-- Time Picker -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/timePicker/jquery-ui-timepicker.js"></script>
        <!-- Scroll Bar -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/scrollbar/jquery.nicescroll.min.js"></script>
        <!-- Text Editor -->
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/textEditor/jquery-te-1.4.0.min.js"></script>
        <style type="text/css">
            .ui-tabs-panel{overflow-y: scroll;}
            .key {
                min-width: 18px;
                height: 18px;
                margin: 2px;
                padding: 2px;
                text-align: center;
                font: 14px/18px sans-serif;
                color: #777;
                background: #EFF0F2;
                border-top: 1px solid #F5F5F5;
                text-shadow: 0px 1px 0px #F5F5F5;
                -webkit-box-shadow: inset 0 0 25px #eee, 0 1px 0 #c3c3c3, 0 2px 0 #c9c9c9, 0 2px 3px #333;
                -moz-box-shadow: inset 0 0 25px #eee, 0 1px 0 #c3c3c3, 0 2px 0 #c9c9c9, 0 2px 3px #333;
                box-shadow: inset 0 0 25px #eee, 0 1px 0 #c3c3c3, 0 2px 0 #c9c9c9, 0 2px 3px #333;
                display: inline-block;
                -moz-border-radius: 1px;
                border-radius: 1px;
            }
            h1 .key {
                width: 42px;
                height: 40px;
                font: 15px/40px sans-serif;
                -moz-border-radius: 5px;
                border-radius: 5px;
            }


            .lang-select-container {
                position: relative;
                display: inline-block;
            }

            .lang-select {
                width: 120px;
                height: 40px;
                padding: 8px 30px 8px 40px;
                border: 1px solid #ddd;
                border-radius: 4px;
                background-color: white;
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'12\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23333\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E');
                background-repeat: no-repeat;
                background-position: right 10px center;
                font-size: 14px;
                color: #333;
                cursor: pointer;
            }
            .lang-select-option-en {
                background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 640 480\'%3E%3Cpath fill=\'%23112f7a\' d=\'M0 0h640v480H0z\'/%3E%3Cpath fill=\'%23fff\' d=\'m120 120h160v160H120zm240 0h160v160H360z\'/%3E%3Cpath fill=\'%23d00\' d=\'m120 280h160v160H120zm240 0h160v160H360z\'/%3E%3C/svg%3E');
                background-repeat: no-repeat;
                background-position: left center;
                padding-left: 25px;
            }
            .lang-select-option-th {
                background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 640 480\'%3E%3Cpath fill=\'%23ed1c24\' d=\'M0 0h640v160H0z\'/%3E%3Cpath fill=\'%230052b5\' d=\'M0 320h640v160H0z\'/%3E%3Cpath fill=\'%23fff\' d=\'M0 160h640v160H0z\'/%3E%3C/svg%3E');
                background-repeat: no-repeat;
                background-position: left center;
                padding-left: 25px;
            }
            .lang-select-icon {
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                pointer-events: none;
                color: #555;
                font-size: 16px;
            }

        </style>
        <script type="text/javascript">
            $(document).ready(function () {
                $("input.integer").live("keydown", function (e) {
                    var key = e.charCode || e.keyCode || 0;
                    // allow backspace, tab, shift, home & end, delete, arrows, numbers and keypad numbers ONLY
                    return (key == 8 || key == 9 || key == 16 || (key >= 35 && key <= 36) || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
                });
                $("input.integer").live("keyup", function (e) {
                    var key = e.charCode || e.keyCode || 0;
                    if($(this).val()!='' && key != 16 && !(key >= 35 && key <= 36)){
                        currentCursorPosition=$(this).caret().end;
                        $(this).val(parseUniInt($(this).val()));
                        $(this).caret({start:currentCursorPosition,end:currentCursorPosition});
                    }
                });
                $("input.number").live("keydown", function (e) {
                    var key = e.charCode || e.keyCode || 0;
                    // allow backspace, tab, shift, home & end, delete, arrows, numbers and keypad numbers ONLY
                    return (key == 8 || key == 9 || key == 16 || (key >= 35 && key <= 36) || key == 46 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 190);
                });
                $("input.number").live("keyup", function (e) {
                    var key = e.charCode || e.keyCode || 0;
                    if(key == 190){
                        currentCursorPosition=$(this).caret().end;
                        $(this).caret({start:currentCursorPosition-1,end:currentCursorPosition});
                        $(this).val($(this).caret().replace("."));
                        $(this).caret({start:currentCursorPosition,end:currentCursorPosition});
                    }
                    if(key == 110){
                        currentCursorPosition=$(this).caret().end;
                        $(this).caret({start:currentCursorPosition,end:currentCursorPosition});
                        $(this).val($(this).caret().replace("."));
                        $(this).caret({start:currentCursorPosition+1,end:currentCursorPosition+1});
                    }
                    if($(this).val()!='' && key != 16 && !(key >= 35 && key <= 36) && key != 48 && key != 96 && key != 190 && key != 110){
                        currentCursorPosition=$(this).caret().end;
                        $(this).val(parseUniFloat($(this).val()));
                        $(this).caret({start:currentCursorPosition,end:currentCursorPosition});
                    }
                });
            });
        </script>
        <script type="text/javascript">
            var clearCookie = false;
            var titles = [];
            var hrefs = [];
            var selectedTabIndex=0;

            function setTabPanelHeight() {
                var windowHeight  = $(window).height();
                // Set Tab Panel Height
                var headerHeight  = $('.ui-layout-north').outerHeight(true);
                var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
                var panelHeight   = windowHeight - headerHeight - tabsNavHeight - 30; /* 30px for extra spacing */
                
                $('.ui-tabs-panel').css({
                    'height': panelHeight + 'px',
                    'max-height': panelHeight + 'px'
                });
            }

            // Debounce function to limit how often the resize handler executes
            function debounce(func, wait) {
                var timeout;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        func.apply(context, args);
                    }, wait);
                };
            }

            function removeEmptyTabs() {
                // Get all tab panels except main_page
                $('.ui-tabs-panel').not('#main_page').each(function() {
                    const $panel = $(this);
                    const panelId = $panel.attr('id');
                    const $tab = $(`#tabList a[href="#${panelId}"]`).closest('li');
                    
                    // Check if panel is empty or contains only hidden inputs
                    const hasVisibleContent = $panel.contents().not('input[type="hidden"]').length > 0;
                    const hasRealContent = $panel.html().trim().replace(/<[^>]+>/g, '').length > 0;
                    
                    if (!hasVisibleContent || !hasRealContent) {
                        // Remove tab and panel
                        $tab.remove();
                        $panel.remove();
                        
                        // If this was the active tab, select first available tab
                        if ($tab.hasClass('ui-tabs-selected')) {
                            $("#tabs").tabs("select", 0);
                        }
                    }
                });
                console.log('Empty tabs removed');
            }

            $(document).ready(function () {
                // Create debounced version of setTabPanelHeight
                var debouncedSetHeight = debounce(setTabPanelHeight, 100);
                // bind save() to window.onunload
                $(window).unload(function(){
                    // save layout state
                    layoutState.save('myLayout');
                    // save tabs to cookie
                    $('#tabs a').each(function() {
                        var title = $(this).text();
                        var href = $.data(this, 'href.tabs');
                        if(href!=undefined){
                            titles.push(title);
                            hrefs.push(href);
                        }
                    });
                    if(clearCookie==true){
                        $.cookie('cookieTitle', null, { expires: 7, path: "/" });
                        $.cookie('cookieHref', null, { expires: 7, path: "/" });
                        $.cookie('cookieTabIndex', null, { expires: 7, path: "/" });
                    }else{
                        $.cookie('cookieTitle', titles, { expires: 7, path: "/" });
                        $.cookie('cookieHref', hrefs, { expires: 7, path: "/" });
                        $.cookie('cookieTabIndex', selectedTabIndex, { expires: 7, path: "/" });
                    }
                });

                // detech screen
                var contentId="#main_page";

                // tab init
                $("#tabs").tabs({
                    cache: true,
                    spinner: '<?php echo ACTION_LOADING; ?>',
                    closable: true,
                    closableClick: function(event, ui) {},
                    remove: function(event, ui) {}
                });

                // tab paging
                $("#tabs").tabs('paging', {
                    cycle: false,
                    follow: false,
                    followOnSelect: true,
                    prevButton: '<span class="ui-icon ui-icon-carat-1-w"></span>',
                    nextButton: '<span class="ui-icon ui-icon-carat-1-e"></span>'
                });

                // tab sort
                $("#tabList").sortable({
                    delay: 1000,
                    axis: "x",
                    items: "li:not(.main_page,.ui-tabs-paging-prev,.ui-tabs-paging-next)",
                    update: function() {}
                });

                // when tab loaded
                $("#tabs").bind("tabsload", function(event, ui) {
                    tabName="";
                    // if no auth
                    if($("#"+ui.panel.id).text()=="No Authentication"){
                        $("#tabs").tabs("remove", ui.index);
                        $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>No Authentication</p>');
                        $("#dialog").dialog({
                            title: '<?php echo DIALOG_INFORMATION; ?>',
                            resizable: false,
                            modal: true,
                            width: 'auto',
                            height: 'auto',
                            open: function(event, ui){
                                $(".ui-dialog-buttonpane").show();
                            },
                            buttons: {
                                '<?php echo ACTION_CLOSE; ?>': function() {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    }
                });

                // when tab closed
                $("#tabs").bind("tabsremove", function(event, ui) {
                    tabName="";
                });

                // when tab added
                $("#tabs").bind("tabsadd", function(event, ui) {
                    $("#tabs").tabs("select",ui.index);
                });

                // when tab selected
                $("#tabs").bind("tabsselect", function(event, ui) {
                    // save selected tab index
                    selectedTabIndex=ui.index;
                    // detech screen
                    contentId="#"+ui.panel.id;
                });

                // when tab removed
                $("#tabs").bind("tabsremove", function(event, ui) {
                    var tmp = $('#tabs ul li.ui-tabs-selected a').attr("href");
                    $("#tabs").tabs("select", 0);
                    $("#tabs").tabs("select", tmp);
                });

                // Set initial height
                setTabPanelHeight();

                // // Use debounced version for resize (executes 100ms after resize stops)
                $(window).resize(debouncedSetHeight);

                $(".blank").click(function(event){
                    event.preventDefault();
                    window.open($(this).attr("href"));
                });

                // Menu Trigger
                const $menuContainer = $('.menu-container');
                const $hamburgerIcon = $('.hamburger-icon');
                const $toggleMenuBtn = $('.toggle-menu');
                const $userActionsBtn = $('.user-profile');
                const $dropdownMenu = $('.user-menu-dropdown-menu');
                const $toggleMobileMenu = $(".mobile-menu-toggle");

                // Function to check if screen is mobile
                function isMobileScreen() {
                    return window.matchMedia("(max-width: 768px)").matches;
                }

                // Function to close mobile menu
                function closeMobileMenu() {
                    if (isMobileScreen()) {
                        $menuContainer.removeClass('mobile-visible');
                    }
                }

                // Collapse menu when toggle button is clicked
                $toggleMenuBtn.on('click', function() {
                    if (isMobileScreen()) {
                        $menuContainer.removeClass('mobile-visible');
                    } else {
                        $menuContainer.addClass('collapsed');
                    }
                    $dropdownMenu.removeClass('show');
                });

                // Toggle menu when hamburger icon is clicked
                $hamburgerIcon.on('click', function() {
                    if (isMobileScreen()) {
                        $menuContainer.toggleClass('mobile-visible');
                    } else {
                        $menuContainer.toggleClass('collapsed');
                    }
                    
                    if (!$menuContainer.hasClass('collapsed') && !$menuContainer.hasClass('mobile-visible')) {
                        $dropdownMenu.removeClass('show');
                    }
                });

                // Handle directory clicks to expand/collapse submenus
                $('.dir').on('click', function() {
                    const $submenu = $(this).next();
                    $(this).toggleClass('expanded');
                    $submenu.toggleClass('expanded');
                });

                // Initialize tooltips for collapsed menu
                $('.menu-item, .dir, .user-profile').on({
                    mouseenter: function() {
                        if ($menuContainer.hasClass('collapsed')) {
                            $(this).find('.tooltip').show();
                        }
                    },
                    mouseleave: function() {
                        if ($menuContainer.hasClass('collapsed')) {
                            $(this).find('.tooltip').hide();
                        }
                    }
                });

                // Toggle user-menu-dropdown menu
                $userActionsBtn.on('click', function(e) {
                    e.stopPropagation();
                    $dropdownMenu.toggleClass('show');
                });

                // Close user-menu-dropdown when clicking outside
                $(document).on('click', function() {
                    $dropdownMenu.removeClass('show');
                });

                // Prevent user-menu-dropdown from closing when clicking inside it
                $dropdownMenu.on('click', function(e) {
                    e.stopPropagation();
                });

                $toggleMobileMenu.on('click', function(e) {
                    $menuContainer.toggleClass('mobile-visible');
                });

                $(".ajax").click(function(event) {
                    event.preventDefault();
                    var obj = $(this);
                    var found = false;

                    // Close mobile menu if on mobile screen
                    closeMobileMenu();
                    
                    if (tabName != $(this).text()) {
                        tabName = $(this).text();
                        
                        $('#tabs a').not("[href=#]").each(function() {
                            if (obj.text() == "<?php echo MENU_DASHBOARD; ?>") {
                                found = true;
                                $("#tabs").tabs("select", 0);
                            } else if (obj.attr("href") == $.data(this, 'href.tabs')) {
                                found = true;
                                $("#tabs").tabs("select", $(this).attr("href"));
                            }
                        });
                        
                        if (found == false) {
                            $("#tabs").tabs("add", $(this).attr("href"), $(this).text());
                        }
                        
                        // Call the debounced version
                        debouncedSetHeight();
                    }
                });
                // Replace Text Main Page
                $(".main_page").find("a").text("<?php echo MENU_DASHBOARD; ?>");
            });
        </script>
    </head>
    <body>
        <script type="text/javascript" src="<?php echo $this->webroot; ?>js/wz_tooltip_v4.js"></script>
        <div class="main-container">
            <div class="menu-container">
                <div class="menu-header">
                    <i class="fas fa-bars hamburger-icon" title="Toggle menu"></i>
                    <div class="logo-container">
                        <img src="<?php echo $this->webroot; ?>img/logo-1.png" alt="Logo" class="logo">
                    </div>
                    <button class="toggle-menu" title="Collapse menu">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                
                <div class="menu-content">
                    <?php echo $this->element('menu'); ?>
                </div>
                
                <!-- User Profile Section -->
                <div class="user-profile-container">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <?php
                            $firstName = isset($user['User']['first_name']) ? $user['User']['first_name'] : '';
                            $lastName = isset($user['User']['last_name']) ? $user['User']['last_name'] : '';
                            $firstWordFirstName = strtok($firstName, ' ');
                            $firstWordLastName = strtok($lastName, ' ');
                            echo $firstWordFirstName[0] . $firstWordLastName[0];
                            ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo $firstName." ".$lastName; ?></div>
                        </div>
                    </div>
                    
                    <!-- user-menu-dropdown Menu -->
                    <div class="user-menu-dropdown-menu">
                        <a href="<?php echo $this->webroot; ?>users/profile" class="user-menu-dropdown-item ajax">
                            <i class="fas fa-user"></i>
                            <span><?php echo GENERAL_MY_PROFILE; ?></span>
                        </a>
                        <a href="<?php echo $this->webroot; ?>users/logout" class="user-menu-dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span><?php echo GENERAL_LOG_OUT; ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="content-area">
                <div class="ui-layout-north">
                    <?php echo $this->element('header'); ?>
                </div>
                <div class="ui-layout-center">
                    <div id="tabs" style="border: none;">
                        <ul id="tabList">
                            <li class="main_page"><a href="#main_page"><?php __($title_for_layout); ?></a></li>
                        </ul>
                        <div id="main_page">
                            <?php echo $content_for_layout; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="mobile-menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Dialog boxes -->
        <div id="dialog" title=""></div>
        <div id="dialog1" title=""></div>
        <div id="dialog2" title=""></div>
        <div id="dialog3" title=""></div>
        <div id="dialog4" title=""></div>
        <div id="dialogModal" title=""></div>
    </body>
</html>