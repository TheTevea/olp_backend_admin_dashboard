<?php
// Authentication
$this->element('check_access');
$allowAgencyBookedNotification = checkAccess($user['User']['id'], 't_tickets', 'agencyApiBooked');
$allowLuckyTicketNotification  = checkAccess($user['User']['id'], 't_tickets', 'luckyTicketNotification');
?>
<script type="text/javascript">
    var tabName     = "";
    var waitForFinalConection = (function () {
        var timers = {};
        return function (callback, ms, uniqueId) {
            if (!uniqueId) {
              uniqueId = "Don't call this twice without a uniqueId";
            }
            if (timers[uniqueId]) {
              clearTimeout (timers[uniqueId]);
            }
            timers[uniqueId] = setTimeout(callback, ms);
        };
    })();
    
    function convertToSeparator(string){
        return string.toString().trim().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }
    
    function checkConnection(){
        var a = a||{};
        a.checkURL = window.location.href.replace('dashboards/index', 'users/connection');
        a.checkInterval = 15000;
        a.msgNot = "No Connection";
        a.msgCon = "Connected";
        getConnection(a);
    }
    
    function getConnection(a){
        var isCheck = 1;
        $.ajax({
            type: "POST",
            dataType: "json",
            url: a.checkURL,
            cache: !1,
            error: function() {
                isCheck = 0;
                waitForFinalConection(function(){
                    // Recheck Conection
                    getConnection(a);
                }, a.checkInterval, "Finish");
            },
            complete: function(){
                if(isCheck == 0){
                    isCheck = 1;
                    $("#connectWarning").css('background', '#FF0000').text(a.msgNot).show();
                }else{
                    $("#connectWarning").css('background', '#03C').text(a.msgCon).fadeOut(10000);
                }
            },
            success: function(result){
                <?php
                if($allowAgencyBookedNotification){
                ?>
                var agencyBooked = result.total;
                $("#agencyBookedTicket").text(agencyBooked);
                <?php
                }
                if($allowLuckyTicketNotification){
                ?>
                var luckyTicket  = result.total_lucky;
                $("#luckyTicketLbl").text(luckyTicket);
                <?php
                }
                ?>
                waitForFinalConection(function(){
                    // Recheck Conection
                    getConnection(a);
                }, a.checkInterval, "Finish");
            }
        });
    }
    
    function preventKeyEnter(){
        // Prevent Input Key Enter
        $("input[type='text']").keypress(function(e){
            if((e.which && e.which == 13) || e.keyCode == 13){
                return false;
            }
        });
    }
    
    function clearTmpTabs(){
        $("#tabs").tabs( "remove" ,$("#tabs").tabs("length")-1);
    }
    
    function replaceSlash(string){
        if(string != ""){
           string = string.toString().trim().replace(/\//g, '\\/');
        }else{
           string = "";
        }
        return string;
    }
    
    function replaceDoubleQuote(string){
        if(string != ""){
           string = string.toString().trim().replace(/"/g, '\\"');
        }else{
           string = "";
        }
        return string;
    }
    
    function replaceNum(str){
        if(str != "" && str != undefined && str != null){
            var str = parseFloat(str.toString().replace(/,/g,""));
        }else{
            var str = 0;
        }
        return str;
    }
    
    function converDicemalJS(value){
        return Math.round(parseFloat(value) * 1000000000)/1000000000;
    }
    
    function converDicemalRound(value){
        value = converDicemalJS(value * 1000);
        if(value.toString().match(/\./)){
            value = value.toString().split(".")[0];
        }
        value = converDicemalJS(parseFloat(value) / 1000);
        return value;
    }
    
    function checkFieldRecord(val){
        var result = true;
        if(val == "" || val == undefined || val == null){
            result = false;
        }
        return result;
    }
    
    // Set Cookie
    function setCookie(cookie, val){
        $.cookie(cookie, val, { expires: 7, path: "/" });
    }
    
    // Use Cookie
    function useCookie(obj, cookie){
        $(obj).val($.cookie(cookie));
    }
    
    function createSysAct(mod, act, status, bug){
        var bugSend = bug.toString().replace(/&nbsp;/g, "").replace(/&gt;/g, "$"); 
        $.ajax({
            type:   "POST",
            url:    "<?php echo $this->base . '/'; ?>users/createSysAct/"+mod+"/"+act+"/"+status,
            data:   "bug="+bugSend
        });
    }
    
    function checkRequireField(fields){
        var result = true;
        if(fields.length > 0){
            $.each(fields, function(key, value) {
                if($("#"+value).val() ==  ""){
                    result = false;
                }
            });
        } else {
            result = false;
        }
        return result;
    }
    
    function checkRequireFieldMulti(fields){
        var result = true;
        if(fields.length > 0){
            $.each(fields, function(key, value) {
                if($("#"+value).val() ==  ""){
                    result = false;
                }
            });
        } else {
            result = false;
        }
        return result;
    }
    
    function alertSelectRequireField(){
        $("#dialog").html('<p style="color:red; font-size:14px;"><?php echo MESSAGE_COMFIRM_INPUT_ALL_REQUIREMENT; ?></p>');
        $("#dialog").dialog({
            title: '<?php echo DIALOG_INFORMATION; ?>',
            resizable: false,
            modal: true,
            closeOnEscape: false,
            width: 'auto',
            height: 'auto',
            position:'center',
            open: function(event, ui){
                $(".ui-dialog-buttonpane").show();
                $(".ui-dialog-titlebar-close").hide();
            },
            buttons: {
                '<?php echo ACTION_CLOSE; ?>': function() {
                    $(this).dialog("close");
                    $(".ui-dialog-titlebar-close").show();
                }
            }
        });
    }
    
    function dialogMessage(message){
        $("#dialog").html('<p style="color:red; font-size:14px;">'+message+'</p>');
        $("#dialog").dialog({
            title: '<?php echo DIALOG_INFORMATION; ?>',
            resizable: false,
            modal: true,
            closeOnEscape: false,
            width: 'auto',
            height: 'auto',
            position:'center',
            open: function(event, ui){
                $(".ui-dialog-buttonpane").show();
                $(".ui-dialog-titlebar-close").show();
            },
            buttons: {
                '<?php echo ACTION_CLOSE; ?>': function() {
                    $(this).dialog("close");
                }
            }
        });
    }
        
    function randomString(string_length) {
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var randomstring = '';
        for (var i=0; i<string_length; i++) {
                  var rnum = Math.floor(Math.random() * chars.length);
                  randomstring += chars.substring(rnum,rnum+1);
        }
        return randomstring;
    }
    
    $(document).ready(function(){
        // Check Connection
        checkConnection();
        $("#lang").change(function(){
            clearCookie = true;
            window.open('<?php echo $this->base; ?>/users/lang/' + $(this).val(), '_self');
        });
        <?php
        if($allowAgencyBookedNotification){
        ?>
        $(".btnViewAgencyBooked").unbind("click").click(function(event){
            event.preventDefault();
            var obj   = $(this);
            var found = false;
            $('#tabs a').not("[href=#]").each(function() {
                if(obj.attr("href") == $.data(this, 'href.tabs')){
                    found=true;
                    $("#tabs").tabs("select", $(this).attr("href"));
                }
            });
            if(found==false){
                $("#tabs").tabs("add", $(this).attr("href"), "<?php echo "Agency Booked"; ?>");
            }
        });
        <?php
        }
        ?>
        // Function Option Hide/Show
        $.fn.showHideDropdownOptions = function(value, canShowOption) { 
            $(this).find('option[value="' + value + '"]').map(function () {
                return $(this).parent('span').length === 0 ? this : null;
            }).wrap('<span>').hide();

            if (canShowOption) {
                $(this).find('option[value="' + value + '"]').unwrap().show();
            } else {
                $(this).find('option[value="' + value + '"]').hide();
            }
       };
       
        // Function Option
        $.fn.filterOptions = function(objCompare, compare, selected, emptyHide) { 
            var object = $(this);
            var compareObj = '';
            if(compare != null && compare != ''){
                compareObj = compare.toString().split(",");
            }
            // Hide by Filter
            object.find("option").removeAttr('selected');
            object.find("option").each(function(){
                if($(this).val() != '' && $(this).val() != 'all'){
                    var value = $(this).val();
                    var compareId  = $(this).attr(objCompare).toString().split(",");
                    if(compareId.indexOf(compare)==-1 && compareObj.indexOf($(this).attr(objCompare).toString())==-1){
                        object.showHideDropdownOptions(value, false);
                    } else {
                        object.showHideDropdownOptions(value, true);
                    }
                }
            });
            // OPTION SELECTED
            if(emptyHide != undefined){
                object.showHideDropdownOptions('', false);
                if(selected == ''){
                    object.find("option").removeAttr('selected');
                } else {
                    object.find('option[value="'+selected+'"]').attr('selected', true);
                }
            } else {
                object.showHideDropdownOptions('', true);
                object.find('option[value="'+selected+'"]').attr('selected', true);
            }
        };
    });
</script>
<table style="width: 100%; height: 100%; margin-top: 5px;" cellspacing="0">
    <tr>
        <td style="vertical-align: top; height: 50px;">
            <div id="showWarning">
                <div style="color: #fff; font-size: 16px; text-align: center; width: 100%; background: #FF0000; display: none;" id="connectWarning">No Connection</div>
            </div>
        </td>
        <td style="vertical-align: top; width: 80%;">
            <table style="width: 100%;">
                <tr>
                    <td></td>
                    <td style="font-size: 12px; font-weight: bold; width: 300px;">
                        <?php
                        if($user['User']['type'] == 2){
                            if($allowAgencyBookedNotification){
                        ?>
                        <!-- <button class="btnViewAgencyBooked" 
                                href="<?php //echo $this->webroot; ?>t_tickets/agencyApiBooked"
                                title="Agency Booked Tickets"
                                style="position: relative; width: 55px; height: 40px; background: #ef4444; padding: 0; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; border-radius: 15px; float: right;">
                            <i class="fas fa-user-tie" style="font-size: 18px; color: white;"></i>
                            <span style="position: absolute; top: -5px; right: 0px; background-color: red; color: white; border-radius: 50%; width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center; line-height: 1; border: 2px solid white;" id="agencyBookedTicket">0</span>
                        </button> -->
                        <?php
                            }
                        } else {
                            if($user['User']['type'] == 3 || $user['User']['type'] == 4){
                                $sqlAg = mysql_query("SELECT * FROM t_agents WHERE t_agents.user_id = ".$user['User']['id']);
                                if(mysql_num_rows($sqlAg)){
                                    $rowAg = mysql_fetch_array($sqlAg);
                                    $sqlBalance = mysql_query("SELECT IFNULL((SELECT SUM(credit - debit) FROM `agency_balances` WHERE t_agency_id = ".$rowAg['id']."), 0)");
                                    $rowBalance = mysql_fetch_array($sqlBalance);
                                    if($rowAg['payment'] == 1){
                                        echo "Balance: ".number_format($rowBalance[0], 2)." $";
                                    } else {
                                        $balance = $rowAg['max_balance'] - ($rowBalance[0] * -1);
                                        $color = "blue";
                                        if($balance < 100){
                                            $color = "red";
                                        }
                                        echo 'Balance: <span style="color: '.$color.';">'.number_format($balance, 2).' $</span> / <span style="color: blue;">'.number_format($rowAg['max_balance'], 2)." $</span>";
                                    }
                                }
                            }
                        }
                        ?>
                    </td>
                    <td style="width: 16px; text-align: center; vertical-align: middle;">
                        <img alt="" src="<?php echo $this->webroot; ?>img/layout/toolbox-divider.gif" style="display: block; margin: 0 auto;" />
                    </td>
                    <td style="width: 120px;">
                        <div class="lang-select-container">
                            <select id="lang" class="lang-select">
                                <option value="en" class="lang-select-option-en" <?php echo $this->Session->read('lang')=="en"?'selected="selected"':''; ?>>English</option>
                                <option value="kh" class="lang-select-option-th" <?php echo $this->Session->read('lang')=="kh"?'selected="selected"':''; ?>>ភាសាខ្មែរ</option>
                            </select>
                            <span class="lang-select-icon">
                                <i class="fas fa-globe"></i>
                            </span>
                        </div>
                    </td>
                    <td style="width: 16px; text-align: center; vertical-align: middle;">
                        <img alt="" src="<?php echo $this->webroot; ?>img/layout/toolbox-divider.gif" style="display: block; margin: 0 auto;" />
                    </td>
                    <td style="width: 16px;"><img alt="" src="<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif" align="absmiddle" style="height: 20px; vertical-align: middle;" class="loader" /></td>
                </tr>
            </table>
        </td>
    </tr>
</table>