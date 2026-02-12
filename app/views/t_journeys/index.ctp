<?php
// Authentication
$this->element('check_access');
$allowAdd=checkAccess($user['User']['id'], $this->params['controller'], 'add');
$tblName = "tbl" . rand();
?>
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/pipeline.js"></script>
<script type="text/javascript">
    var oTableTJourney;
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#<?php echo $tblName; ?> td:first-child").addClass('first');
        $("#journeyFilterFrom, #journeyFilterTo").chosen({width: 200});
        $("#journeyFilterStatus").chosen({width: 180});
        oTableTJourney = $("#<?php echo $tblName; ?>").dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $this->base.'/'.$this->params['controller']; ?>/ajax/",
            "fnServerData": fnDataTablesPipeline,
            "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                $("#lblRefreshTJourney").text("<?php echo GENERAL_SEARCH; ?>");
                $("#btnRefreshTJourney").attr("disabled", false);
                $("#<?php echo $tblName; ?> td:first-child").addClass('first');
                $("#<?php echo $tblName; ?> td:last-child").css("white-space", "nowrap");
                $(".chkJourneyList").each(function(){
                    if($(this).is(':checked')){
                        $(this).closest("tr").css("background", "#f4ffab");
                    }
                });

                $(".chkJourneyList").unbind("click").click(function(){
                    var isHighlight = 0;
                    var id = $(this).attr('data');
                    if($(this).is(':checked')){
                        isHighlight = 1;
                        $(this).closest("tr").css("background", "#f4ffab");
                    } else {
                        $(this).closest("tr").css("background", "none");
                    }
                    $.ajax({
                        type: "GET",
                        url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/saveHighlight/"+id+"/"+isHighlight,
                        data: "",
                        beforeSend: function(){
                            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                        },
                        success: function(result){
                            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                        }
                    });
                });

                $(".btnViewTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr('rel');
                    var leftPanel=$(this).parent().parent().parent().parent().parent().parent().parent();
                    var rightPanel=leftPanel.parent().find(".rightPanel");
                    leftPanel.hide("slide", { direction: "left" }, 500, function() {
                        rightPanel.show();
                    });
                    rightPanel.html("<?php echo ACTION_LOADING; ?>");
                    rightPanel.load("<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/view/" + id);
                });
                $(".btnEditTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr('rel');
                    var leftPanel=$(this).parent().parent().parent().parent().parent().parent().parent();
                    var rightPanel=leftPanel.parent().find(".rightPanel");
                    leftPanel.hide("slide", { direction: "left" }, 500, function() {
                        rightPanel.show();
                    });
                    rightPanel.html("<?php echo ACTION_LOADING; ?>");
                    rightPanel.load("<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/edit/" + id);
                });
//                $(".btnFareEventTJourney").unbind('click').click(function(event){
//                    event.preventDefault();
//                    var id = $(this).attr('rel');
//                    var leftPanel  = $(this).parent().parent().parent().parent().parent().parent().parent();
//                    var rightPanel = leftPanel.parent().find(".rightPanel");
//                    leftPanel.hide("slide", { direction: "left" }, 500, function() {
//                        rightPanel.show();
//                    });
//                    rightPanel.html("<?php echo ACTION_LOADING; ?>");
//                    rightPanel.load("<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/fareEvent/" + id);
//                });
                $(".btnUpdateTransportationTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr('rel');
                    var leftPanel  = $(this).parent().parent().parent().parent().parent().parent().parent();
                    var rightPanel = leftPanel.parent().find(".rightPanel");
                    leftPanel.hide("slide", { direction: "left" }, 500, function() {
                        rightPanel.show();
                    });
                    rightPanel.html("<?php echo ACTION_LOADING; ?>");
                    rightPanel.load("<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/changeTransportation/" + id);
                });
                $(".btnDeleteTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr('rel');
                    var name = $(this).attr('name');
                    $("#dialog").dialog('option', 'title', '<?php echo DIALOG_CONFIRMATION; ?>');
                    $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_CONFIRM_DELETE; ?> <b>' + name + '</b>?</p>');
                    $("#dialog").dialog({
                        title: '<?php echo DIALOG_CONFIRMATION; ?>',
                        resizable: false,
                        modal: true,
                        width: 'auto',
                        height: 'auto',
                        open: function(event, ui){
                            $(".ui-dialog-buttonpane").show();
                        },
                        buttons: {
                            '<?php echo ACTION_VOID; ?>': function() {
                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/delete/" + id,
                                    data: "",
                                    beforeSend: function(){
                                        $("#dialog").dialog("close");
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                    },
                                    success: function(result){
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        // alert message
                                        if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_DELETED; ?>'){
                                            createSysAct('TJourney', 'Delete', 2, result);
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                        }else {
                                            createSysAct('TJourney', 'Delete', 1, '');
                                            // alert message
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                                        }
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    }
                                });
                            },
                            '<?php echo ACTION_CANCEL; ?>': function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                });

                $(".btnDeleteForeverTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr('rel');
                    var name = $(this).attr('name');
                    $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Do you want to delete forever for <b>' + name + '</b>?</p>');
                    $("#dialog").dialog({
                        title: '<?php echo DIALOG_CONFIRMATION; ?>',
                        resizable: false,
                        modal: true,
                        width: 'auto',
                        height: 'auto',
                        open: function(event, ui){
                            $(".ui-dialog-buttonpane").show();
                        },
                        buttons: {
                            '<?php echo ACTION_VOID; ?>': function() {
                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/deleteForever/" + id,
                                    data: "",
                                    beforeSend: function(){
                                        $("#dialog").dialog("close");
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                    },
                                    success: function(result){
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        // alert message
                                        if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_DELETED; ?>'){
                                            createSysAct('TJourney', 'Delete Forver', 2, result);
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                        }else {
                                            createSysAct('TJourney', 'Delete Forver', 1, '');
                                            // alert message
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                                        }
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    }
                                });
                            },
                            '<?php echo ACTION_CANCEL; ?>': function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                });
                
                $(".btnUpdateStatusTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id     = $(this).attr('rel');
                    var name   = $(this).attr('name');
                    var status = $(this).attr('status');
                    var update = 1;
                    if(status == '1'){
                        update = 2;
                        $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_CONFIRM_UPDATE_STATUS; ?> <b>' + name + '</b>?</p>');
                        $("#dialog").dialog({
                            title: '<?php echo DIALOG_CONFIRMATION; ?>',
                            resizable: false,
                            modal: true,
                            width: 'auto',
                            height: 'auto',
                            open: function(event, ui){
                                $(".ui-dialog-buttonpane").show();
                            },
                            buttons: {
                                '<?php echo ACTION_YES; ?>': function() {
                                    $.ajax({
                                        dataType: "json",
                                        type: "POST",
                                        url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/updateStatus/" + id + "/" + update,
                                        data: "",
                                        beforeSend: function(){
                                            $("#dialog").dialog("close");
                                            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                        },
                                        error: function (result) {
                                            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                            createSysAct('TJourney', 'Update Status', 2, result.responseText);
                                            oCache.iCacheLower = -1;
                                            oTableTJourney.fnDraw(false);
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                            $("#dialog").dialog({
                                                title: '<?php echo DIALOG_INFORMATION; ?>',
                                                resizable: false,
                                                modal: true,
                                                width: 'auto',
                                                height: 'auto',
                                                position:'center',
                                                closeOnEscape: true,
                                                open: function(event, ui){
                                                    $(".ui-dialog-buttonpane").show(); $(".ui-dialog-titlebar-close").show();
                                                },
                                                buttons: {
                                                    '<?php echo ACTION_CLOSE; ?>': function() {
                                                        $("meta[http-equiv='refresh']").attr('content','0');
                                                        $(this).dialog("close");
                                                    }
                                                }
                                            });
                                        },
                                        success: function(result){
                                            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                            oCache.iCacheLower = -1;
                                            oTableTJourney.fnDraw(false);
                                            createSysAct('TJourney', 'Update Status', 1, "");
                                            if(result.error == '1'){
                                                $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?></p>');
                                            } else {
                                                $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?></p>');
                                            }
                                            $("#dialog").dialog({
                                                title: '<?php echo DIALOG_INFORMATION; ?>',
                                                resizable: false,
                                                modal: true,
                                                width: 'auto',
                                                height: 'auto',
                                                buttons: {
                                                    '<?php echo ACTION_CLOSE; ?>': function() {
                                                        $(this).dialog("close");
                                                    }
                                                }
                                            });
                                        }
                                    });
                                },
                                '<?php echo ACTION_CANCEL; ?>': function() {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            type: "GET",
                            url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/changeStatus/"+id,
                            beforeSend: function(){
                                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                // modal box - open
                                $("#dialogModal").html('<p style="text-align: center;"><img alt="" src="<?php echo $this->webroot; ?>img/ajax-loader.gif" /></p>');
                                $("#dialogModal").dialog({
                                    title: '<?php echo DIALOG_LOADING; ?>',
                                    resizable: false,
                                    modal: true,
                                    closeOnEscape: false,
                                    width: 180,
                                    height: 100,
                                    open: function(event, ui){
                                        $(".ui-dialog-buttonpane").show();
                                        $(".ui-dialog-titlebar-close").hide();
                                    },
                                    close: function(event, ui){
                                        $(".ui-dialog-titlebar-close").show();
                                    }
                                });
                            },
                            success: function(result){
                                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                $("#dialogModal").dialog("close");
                                $("#dialog").html(result);
                                $("#dialog").dialog({
                                    title: "<?php echo TABLE_UPDATE_STATUS; ?> "+name,
                                    resizable: false,
                                    modal: true,
                                    width: 500,
                                    height: 300,
                                    buttons: {
                                        '<?php echo ACTION_CANCEL; ?>': function() {
                                            $(this).dialog("close");
                                        },
                                        '<?php echo ACTION_SAVE; ?>': function() {
                                            var validateBack = $("#frmChangeStatusJourney").validationEngine("validate");
                                            if(!validateBack){
                                                return false;
                                            }else{
                                                $("#changeStatusJourneyStart, #changeStatusJourneyEnd").datepicker("option", "dateFormat", "yy-mm-dd");
                                                var post = $("#frmChangeStatusJourney").serialize();
                                                $(this).dialog("close");
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/changeStatus/"+id,
                                                    data: post,
                                                    beforeSend: function(){
                                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                                        // modal box - open
                                                        $("#dialogModal").html('<p style="text-align: center;"><img alt="" src="<?php echo $this->webroot; ?>img/ajax-loader.gif" /></p>');
                                                        $("#dialogModal").dialog({
                                                            title: '<?php echo DIALOG_LOADING; ?>',
                                                            resizable: false,
                                                            modal: true,
                                                            closeOnEscape: false,
                                                            width: 180,
                                                            height: 100,
                                                            open: function(event, ui){
                                                                $(".ui-dialog-buttonpane").show();
                                                                $(".ui-dialog-titlebar-close").hide();
                                                            },
                                                            close: function(event, ui){
                                                                $(".ui-dialog-titlebar-close").show();
                                                            }
                                                        });
                                                    },
                                                    success: function(result){
                                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                                        oCache.iCacheLower = -1;
                                                        oTableTJourney.fnDraw(false);
                                                        createSysAct('TJourney', 'Change Status Journey', 1, result);
                                                        $("#dialogModal").dialog("close");
                                                        $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                                                        $("#dialog").dialog({
                                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                                            resizable: false,
                                                            modal: true,
                                                            width: 'auto',
                                                            height: 'auto',
                                                            buttons: {
                                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                                    $(this).dialog("close");
                                                                }
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
                $(".btnCloneTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var routeId   = $(this).attr('rel');
                    var leftPanel=$(this).parent().parent().parent().parent().parent().parent().parent();
                    var rightPanel=leftPanel.parent().find(".rightPanel");
                    leftPanel.hide("slide", { direction: "left" }, 500, function() {
                        rightPanel.show();
                    });
                    rightPanel.html("<?php echo ACTION_LOADING; ?>");
                    rightPanel.load("<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/add/" + routeId);
                });
                $(".btnUndoVoidTJourney").unbind('click').click(function(event){
                    event.preventDefault();
                    var id = $(this).attr('rel');
                    var name = $(this).attr('name');
                    $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_CONFIRM_UNDO_VOID; ?> <b>' + name + '</b>?</p>');
                    $("#dialog").dialog({
                        title: '<?php echo DIALOG_CONFIRMATION; ?>',
                        resizable: false,
                        modal: true,
                        width: 'auto',
                        height: 'auto',
                        open: function(event, ui){
                            $(".ui-dialog-buttonpane").show();
                        },
                        buttons: {
                            '<?php echo ACTION_YES; ?>': function() {
                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/undoVoid/" + id,
                                    data: "",
                                    beforeSend: function(){
                                        $("#dialog").dialog("close");
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                    },
                                    success: function(result){
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        // alert message
                                        if(result != '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>' && result != '<?php echo MESSAGE_DATA_INVALID; ?>'){
                                            createSysAct('TJourney', 'Undo Void', 2, result);
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                        }else {
                                            createSysAct('TJourney', 'Undo Void', 1, '');
                                            // alert message
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>'+result+'</p>');
                                        }
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    }
                                });
                            },
                            '<?php echo ACTION_CANCEL; ?>': function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                });

                $(".btnUpdateNoteTJourney").unbind("click").click(function(event){
                    event.preventDefault();
                    var id   = $(this).attr('rel');
                    var note = $(this).attr('note').replace(/{dblquote}/g,'"');
                    $("#dialog").html("<textarea style='width:350px; height: 200px;' id='NoteBusSchedule'>"+note+"</textarea>").dialog({
                        title: '<?php echo TABLE_NOTE; ?>',
                        resizable: false,
                        modal: true,
                        width: 'auto',
                        height: 'auto',
                        position:'center',
                        open: function(event, ui){
                            $(".ui-dialog-buttonpane").show();
                        },
                        buttons: {
                            '<?php echo ACTION_OK; ?>': function() {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/updateNote/" + id,
                                    data: "note="+ $("#NoteBusSchedule").val(),
                                    beforeSend: function(){
                                        $("#dialog").dialog("close");
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                    },
                                    success: function(result){
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                    }
                                });
                            }
                        }
                    });
                });


                $(".btnAllowPricePeriodTJourney").unbind("click").click(function(event){
                    event.preventDefault();
                    var id   = $(this).attr('rel');
                    var name = $(this).attr('name');
                    var endPoint = "updatePricePeriod";
                    $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Do you want to allow price period for <b>' + name + '</b>?</p>');
                    $("#dialog").dialog({
                        title: '<?php echo DIALOG_CONFIRMATION; ?>',
                        resizable: false,
                        modal: true,
                        width: 'auto',
                        height: 'auto',
                        open: function(event, ui){
                            $(".ui-dialog-buttonpane").show();
                        },
                        buttons: {
                            '<?php echo ACTION_YES; ?>': function() {
                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/"+endPoint+"/"+id+"/1",
                                    data: "",
                                    beforeSend: function(){
                                        $("#dialog").dialog("close");
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                    },
                                    error: function (result) {
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        createSysAct('TJourney', 'Allow Price Period', 2, result.responseText);
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            position:'center',
                                            closeOnEscape: true,
                                            open: function(event, ui){
                                                $(".ui-dialog-buttonpane").show(); $(".ui-dialog-titlebar-close").show();
                                            },
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $("meta[http-equiv='refresh']").attr('content','0');
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    },
                                    success: function(result){
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        createSysAct('TJourney', 'Allow Price Period', 1, '');
                                        // alert message
                                        if(result.error == '1'){
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?></p>');
                                        } else {
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?></p>');
                                        }
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    }
                                });
                            },
                            '<?php echo ACTION_NO; ?>': function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                });
                
                $(".btnUnAllowPricePeriodTJourney").unbind("click").click(function(event){
                    event.preventDefault();
                    var id   = $(this).attr('rel');
                    var name = $(this).attr('name');
                    var endPoint  = "updatePricePeriod";
                    $("#dialog").html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Do you want to disable price period for <b>' + name + '</b>?</p>');
                    $("#dialog").dialog({
                        title: '<?php echo DIALOG_CONFIRMATION; ?>',
                        resizable: false,
                        modal: true,
                        width: 'auto',
                        height: 'auto',
                        open: function(event, ui){
                            $(".ui-dialog-buttonpane").show();
                        },
                        buttons: {
                            '<?php echo ACTION_YES; ?>': function() {
                                $.ajax({
                                    type: "GET",
                                    url: "<?php echo $this->base.'/'.$this->params['controller']; ?>/"+endPoint+"/"+id+"/3",
                                    data: "",
                                    beforeSend: function(){
                                        $("#dialog").dialog("close");
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
                                    },
                                    error: function (result) {
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        createSysAct('TJourney', 'Disable Price Period', 2, result.responseText);
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_PROBLEM; ?></p>');
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            position:'center',
                                            closeOnEscape: true,
                                            open: function(event, ui){
                                                $(".ui-dialog-buttonpane").show(); $(".ui-dialog-titlebar-close").show();
                                            },
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $("meta[http-equiv='refresh']").attr('content','0');
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    },
                                    success: function(result){
                                        $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
                                        oCache.iCacheLower = -1;
                                        oTableTJourney.fnDraw(false);
                                        createSysAct('TJourney', 'Disable Price Period', 1, '');
                                        // alert message
                                        if(result.error == '1'){
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?></p>');
                                        } else {
                                            $("#dialog").html('<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span><?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?></p>');
                                        }
                                        $("#dialog").dialog({
                                            title: '<?php echo DIALOG_INFORMATION; ?>',
                                            resizable: false,
                                            modal: true,
                                            width: 'auto',
                                            height: 'auto',
                                            buttons: {
                                                '<?php echo ACTION_CLOSE; ?>': function() {
                                                    $(this).dialog("close");
                                                }
                                            }
                                        });
                                    }
                                });
                            },
                            '<?php echo ACTION_NO; ?>': function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                });

                return sPre;
            },
            "aoColumnDefs": [{
                "sType": "numeric", "aTargets": [ 0 ],
                "bSortable": false, "aTargets": [ 0,-1 ]
            }]
        });
        $(".btnAddTJourney").unbind('click').click(function(event){
            event.preventDefault();
            var routeId   = $(this).attr('rel');
            var leftPanel=$(this).parent().parent().parent().parent();
            var rightPanel=leftPanel.parent().find(".rightPanel");
            leftPanel.hide("slide", { direction: "left" }, 500, function() {
                rightPanel.show();
            });
            rightPanel.html("<?php echo ACTION_LOADING; ?>");
            rightPanel.load("<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/add/" + routeId);
        });
        
        $("#journeyFilterBranch").filterOptions('com', '0', '');
        // Company Change
        $("#journeyFilterCompany").unbind('change').change(function(){
            var companyId = $(this).val();
            $("#journeyFilterBranch").filterOptions('com', companyId, '');
        });

        $("#btnRefreshTJourney").unbind("click").click(function(event){
            filterJourney();
        });
    });
    
    function filterJourney(){
        $("#lblRefreshTJourney").text("Loading..");
        $("#btnRefreshTJourney").attr("disabled", true);
        var status = "all";
        if($("#journeyFilterStatus").val() != null){
            status = $("#journeyFilterStatus").val();
        }
        var Tablesetting = oTableTJourney.fnSettings();
        Tablesetting.sAjaxSource = "<?php echo $this->base . '/' . $this->params['controller']; ?>/ajax/"+$("#journeyFilterFrom").val()+"/"+$("#journeyFilterTo").val()+"/"+status+"/"+$("#journeyFilterType").val()+"/"+$("#journeyFilterChecked").val();
        oCache.iCacheLower = -1;
        oTableTJourney.fnDraw(false);
    }
</script>
<div class="leftPanel">
    <!-- Header Filter -->
    <?php if($allowAdd){ ?>
    <div class="filter-container">
        <div class="filter-row">
            <div class="filter-group">
                <button type="button" class="positive-btn btnAddTJourney">
                    <i class="fas fa-plus"></i>
                    <span><?php echo MENU_JOURNEY_ADD; ?></span>
                </button>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- Header Filter -->
    <div class="filter-container">
        <!-- Status Filter -->
        <div class="filter-group">
            <select id="journeyFilterFrom">
                <option value="all"><?php echo "From"; ?></option>
                <?php
                foreach($tDestinations AS $tDestination){
                    echo '<option value="'.$tDestination['TDestination']['id'].'">'.$tDestination['TDestination']['name'].'</option>';
                }
                ?>
            </select>   
        </div>
        <!-- Status Filter -->
        <div class="filter-group">
            <select id="journeyFilterTo">
                <option value="all"><?php echo "To"; ?></option>
                <?php
                foreach($tDestinations AS $tDestination){
                    echo '<option value="'.$tDestination['TDestination']['id'].'">'.$tDestination['TDestination']['name'].'</option>';
                }
                ?>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="filter-group">
            <select id="journeyFilterStatus" multiple="" data-placeholder="<?php echo TABLE_STATUS; ?>">
                <option value="1"><?php echo TABLE_ACTIVE; ?></option>
                <option value="2"><?php echo TABLE_INACTIVE; ?></option>
                <option value="0"><?php echo "Void"; ?></option>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="filter-group">
            <select id="journeyFilterType">
                <option value="all"><?php echo TABLE_TYPE; ?></option>
                <option value="1"><?php echo "Direct"; ?></option>
                <option value="3"><?php echo "Direct Multi Route"; ?></option>
                <option value="2"><?php echo "Transit"; ?></option>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="filter-group">
            <select id="journeyFilterChecked">
                <option value="all"><?php echo "Check Box"; ?></option>
                <option value="1"><?php echo ACTION_YES; ?></option>
                <option value="0"><?php echo ACTION_NO; ?></option>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="filter-group">
            <button type="button" class="positive-btn" id="btnRefreshTJourney">
                <i class="fas fa-search"></i>
                <span id="lblRefreshTJourney"><?php echo ACTION_LOADING; ?></span>
            </button>
        </div>
    </div>
    <div id="dynamic">
        <table id="<?php echo $tblName; ?>" class="table" cellspacing="0">
            <thead>
                <tr>
                    <th class="first"><?php echo TABLE_NO; ?></th>
                    <th><?php echo GENERAL_DESCRIPTION; ?></th>
                    <th style="width: 200px !important;"><?php echo REPORT_FROM; ?></th>
                    <th style="width: 200px !important;"><?php echo REPORT_TO; ?></th>
                    <th style="width: 100px !important;"><?php echo TABLE_DEPARTURE; ?></th>
                    <th style="width: 100px !important;"><?php echo TABLE_ARRIVAL_TIME; ?></th>
                    <th style="width: 250px !important;"><?php echo "Nation Route"; ?></th>
                    <th style="width: 100px !important;"><?php echo TABLE_PRICE; ?></th>
                    <th style="width: 100px !important;"><?php echo "Mark Up"; ?></th>
                    <th style="width: 100px !important;"><?php echo "Sell Price"; ?></th>
                    <th style="width: 100px !important;"><?php echo TABLE_TYPE; ?></th>
                    <th style="width: 100px !important;"><?php echo "Access"; ?></th>
                    <th style="width: 100px !important;"><?php echo "Price Inc VAT"; ?></th>
                    <th style="width: 100px !important;"><?php echo TABLE_STATUS; ?></th>
                    <th style="width: 110px !important;"><?php echo ACTION_ACTION; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="14" class="dataTables_empty"><?php echo TABLE_LOADING; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br />
    <br />
</div>
<div class="rightPanel" style="height: 100%; overflow: hidden;"></div>