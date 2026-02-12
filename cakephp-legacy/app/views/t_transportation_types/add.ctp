<?php 
echo $this->element('prevent_multiple_submit'); 
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$link = $protocol . "://" . $_SERVER['HTTP_HOST'] . str_replace("/t_transportation_types/add/","",$_SERVER['REQUEST_URI']);
?>
<style type="text/css" media="screen">
    .labelDropTTransportationType {
        width: 70px;
        height: 30px;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        border: 1px solid #000;
        float: left;
        margin-left: 5px;
        margin-top: 5px;
        text-align: center;
        cursor: move;
        user-select: none;
    }
    
    .chairTTransportationType {
        width: 50px; 
        height: 80px;
        float: left;
        margin-left: 5px;
        margin-top: 5px;
        border: 1px #000 solid;
        text-align: center;
        cursor: move;
        user-select: none;
    }
    
    .chairLabel {
        width: 100%; 
        text-align: center; 
        margin-bottom: 3px; 
        margin-top: 3px;
    }
    
    .chairImg {
        width: 35px; 
        height: 30px;
        margin: 0px auto;
        font-size: 12px;
        font-weight: bold;
        background-image: url("<?php echo $this->webroot; ?>img/button/seating-active-25.png");
        background-repeat: no-repeat;
        text-align: left;
    }
    
    .chairRemove {
        width: 100%; 
        margin-top: 3px;
    }
    
    #layoutContainerTTransportationType div.ui-draggable, 
    #layoutContainerTTransportationType div.ui-draggable-dragging {
        font-size: 12px;
        font-weight: bold;
        margin-left: 5px;
        padding-top: 5px;
        padding-left: 5px;
        z-index: 1000;
    }
    
    #layoutContainerTTransportationType .ui-draggable-disabled, 
    #layoutContainerTTransportationType .ui-state-disabled {
        opacity: 1;
    }
    
    .removeChairTTransportationType {
        display: none;
        cursor: pointer;
        width: 16px;
    }
    
    .layoutSelectTTransportationType{
        background-color: #79b7e7 !important;
    }

    #sortablePhoto {
        list-style-type: none;
        margin: 0; 
        padding: 0;
        margin-right: 10px; 
        width: 100%;
    }    
    #sortablePhoto li { 
        margin: 0px; 
        padding: 0px; 
        font-size: 1.2em; 
        width: 105px; 
        cursor: pointer;
        float: left; 
    }
    
    .hovered {
        background-color: #e2f3ff;
    }
    
    .ui-draggable-dragging {
        opacity: 0.8;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    .btnLayoutTooltip {
        position: relative;
        display: inline-block;
    }

    .btnLayoutTooltip .btnLayoutTooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .btnLayoutTooltip:hover .btnLayoutTooltiptext {
        visibility: visible;
        opacity: 1;
    }

    .boxLeftTTransportationType {
        width: 25%;
        margin-right: 10px;
    }

    .boxRightTTransportationType {
        width: 70%;
    }

    .boxLeftTTransportationType {
        width: 340px;
    }

    .boxLeftTTransportationType, .boxRightTTransportationType {
        border: 1px solid #000;
        min-height: 400px;
        padding: 5px;
        margin-bottom: 15px;
        box-sizing: border-box;
    }

    /* Desktop layout */
    @media (min-width: 768px) {
        .boxLeftTTransportationType {
            width: 340px;
            float: left;
            margin-right: 10px;
        }
        
        .boxRightTTransportationType {
            width: calc(100% - 360px);
            float: left;
        }
    }

    /* Mobile layout */
    @media (max-width: 767px) {
        .boxLeftTTransportationType, 
        .boxRightTTransportationType {
            width: 100%;
            float: none;
            margin-right: 0;
        }
    }

    .level-tab {
        display: inline-block;
        padding: 0px 5px;
        cursor: pointer;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 4px 4px 0 0;
        margin-right: 2px;
        height: 35px;
        line-height: 35px;
    }

    .level-tab.active {
        background-color: #003366;
        color: white;
        border-color: #003366;
    }
    
    .level-name-input {
        background-color: transparent;
        border: none;
        color: inherit;
        font-family: inherit;
        font-size: inherit;
        font-weight: bold;
        text-align: center;
        width: 90px !important;
        outline: none;
        padding: 6px 0;
    }
    .level-tab:not(.active) .level-name-input {
        color: #333;
    }
</style> 

<script type="text/javascript">
    $(document).ready(function(){
        // Constants
        const MAX_SEATS = 200;
        const MAX_ROWS_COLS = 50;
        
        // Cache DOM elements
        const $layoutContainer = $('#layoutContainerTTransportationType');
        const $seatContainer = $('#layoutSeatTTransportationType');
        let currentLevel = 1;
        const $form = $("#TTransportationTypeAddForm");
        
        // Prevent Key Enter
        preventKeyEnter();
        
        // Initialize plugins
        $("#TTransportationTypeAmenityId").chosen({width: 350});
        $(".integer").autoNumeric({mDec: 0, aSep: ','});
        $(".btnSaveTTransportationType").unbind("click").click(function(event){
            $form.submit();
        });
        
        // Form validation
        $form.validationEngine('attach', {
            isOverflown: true,
            overflownDIV: ".ui-tabs-panel"
        });
        
        // Form submission
        $form.ajaxForm({
            beforeSerialize: function($form, options) {
                $(".integer").each(function(){
                    $(this).val($(this).val().replace(/,/g,""));
                });
                $("#TTransportationTypeLayout").val(convertLayoutToJsonTTransportationType());
            },
            beforeSubmit: function(arr, $form, options) {
                $(".txtSaveTTransportationType").html("<?php echo ACTION_LOADING; ?>");
                $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
            },
            success: handleFormSubmissionResult
        });
        
        // Back button
        $(".btnBackTTransportationType").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTTransportationType.fnDraw(false);
            $(this).closest(".rightPanel").hide().empty()
                   .siblings(".leftPanel").show("slide", { direction: "left" }, 500);
        });
        
        // Event bindings
        $("#TTransportationTypeNumberOfSeat").blur(handleSeatNumberChange);
        $("#TTransportationTypeTotalLevels").blur(generateDefaultTableTTransportationType);
        $(".btnGenerateLayoutDropTTransportationType").click(generateLayout);
        $(".btnRowPlusLayoutDropTTransportationType").click(rowPlusTTransportationType);
        $(".btnRowCutLayoutDropTTransportationType").click(rowCutTTransportationType);
        $(".btnColPlusLayoutDropTTransportationType").click(colPlusTTransportationType);
        $(".btnColCutLayoutDropTTransportationType").click(colCutTTransportationType);
        $(".btnMergeLayoutDropTTransportationType").click(mergeTTransportationType);
        $(".btnUnmergeLayoutDropTTransportationType").click(unMergeTTransportationType);
        $(".btnAddLevel").click(addLevel);
        $(".btnRemoveLevel").click(removeLevel);
        
        // Initialize drag and drop
        resetDragTTransportationType();
        generateDefaultTableTTransportationType();
        
        // Image upload handlers
        initializeImageUploads();
        
        // Touch support for mobile devices
        if ('ontouchstart' in window) {
            $.ui.draggable.prototype._mouseStart = function(event) {
                return $.ui.draggable.prototype._touchStart.apply(this, [event.originalEvent.changedTouches[0]]);
            };
        }
        
        // Functions
        
        function handleFormSubmissionResult(result) {
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
            $(".btnBackTTransportationType").click();
            
            const successMessages = [
                '<?php echo MESSAGE_DATA_HAS_BEEN_SAVED; ?>',
                '<?php echo MESSAGE_DATA_COULD_NOT_BE_SAVED; ?>',
                '<?php echo MESSAGE_DATA_ALREADY_EXISTS_IN_THE_SYSTEM; ?>'
            ];
            
            if (!successMessages.includes(result)) {
                createSysAct('Transportation Type', 'Add', 2, result);
                showDialog('<?php echo MESSAGE_PROBLEM; ?>', '<?php echo DIALOG_INFORMATION; ?>');
            } else {
                createSysAct('Transportation Type', 'Add', 1, '');
                showDialog(result, '<?php echo DIALOG_INFORMATION; ?>');
            }
        }
        
        function showDialog(message, title) {
            $("#dialog").html(`<p><span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 20px 0;"></span>${message}</p>`)
                .dialog({
                    title: title,
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
        
        function showConfirmDialog(message, title, confirmCallback) {
            $("#dialog").html(`<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>${message}</p>`)
                .dialog({
                    title: title,
                    resizable: false,
                    modal: true,
                    buttons: {
                        '<?php echo ACTION_YES; ?>': function() {
                            confirmCallback();
                            $(this).dialog("close");
                        },
                        '<?php echo ACTION_NO; ?>': function() {
                            $(this).dialog("close");
                        }
                    }
                });
        }

        function handleSeatNumberChange() {
            const seatCount = parseInt($(this).val().replace(/,/g,"")) || 0;
            if (seatCount < 1 || seatCount > MAX_SEATS) {
                showDialog(`Please enter a valid seat number between 1 and ${MAX_SEATS}`, '<?php echo DIALOG_INFORMATION; ?>');
                return;
            }
            generateSeatTTransportationType();
            generateDefaultTableTTransportationType();
            resetDragTTransportationType();
        }
        
        function generateLayout(event) {
            event.preventDefault();
            const totalRow = Math.min(replaceNum($("#layoutDropTTransportationTypeRow").val()), MAX_ROWS_COLS);
            const totalColumn = Math.min(replaceNum($("#layoutDropTTransportationTypeColumn").val()), MAX_ROWS_COLS);
            
            if(totalRow > 0 && totalColumn > 0){
                $(this).find('span').text('<?php echo ACTION_LOADING; ?>');
                generateSeatTTransportationType();
                generateDefaultTableTTransportationType();
                resetDragTTransportationType();
                $(this).find('span').text('Generate');
            }
        }
        
        function resetDragTTransportationType(){
            $('[id^="labelDropTTransportationType"]').removeAttr("style").css('background', 'none');
            
            const dragElements = [
                {id: '#labelDropTTransportationTypeCapitain', label: 'Capitain'},
                {id: '#labelDropTTransportationTypeToilet', label: 'Toilet'},
                {id: '#labelDropTTransportationTypeHostess', label: 'Hostess'},
                {id: '#labelDropTTransportationTypeDownStair', label: 'DownStair'},
                {id: '#labelDropTTransportationTypeUpStair', label: 'UpStair'},
                {id: '#labelDropTTransportationTypeDoor', label: 'Door'}
            ];
            
            dragElements.forEach(item => {
                generateDragTTransportationType(item.id, item.label);
            });
            
            eventRemoveDragPosition();
        }
        
        function eventRemoveDragPosition(){
            $(".removeChairTTransportationType").off('click').on('click',function(){
                const $chair = $(this).closest(".chairTTransportationType, .labelDropTTransportationType");
                const dragNum = $chair.find(".chairImg span").text();
                const columDrop = "#" + $chair.find(".layoutDropSeat").text();
                
                const isLabel = isNaN(dragNum);
                const chairDrag = isLabel ? `#labelDropTTransportationType${dragNum}` : `#chairTTransportationType${dragNum}`;
                
                // Reset drag element
                $(chairDrag).removeAttr("style")
                    .find("input").prop('disabled', false)
                    .end().find('.removeChairTTransportationType').hide()
                    .end().find('.layoutDropSeat').text('')
                    .end().css('visibility', 'visible');
                
                // Reset drop area
                $(columDrop).find("span.BoatNumber, span.BoatLabel").text('')
                    .end().find(".layoutDisplaySeat").empty()
                    .end().droppable("enable");
                
                // Reinitialize drag
                generateDragTTransportationType(chairDrag, dragNum);
            });
        }
        
        function handleChairTTransportationTypeDrop(event, ui) {
            const number = ui.draggable.data('number');
            const isLabel = isNaN(number);
            const dragSelector = isLabel ? `#labelDropTTransportationType${number}` : `#chairTTransportationType${number}`;
            const $dragElement = $(dragSelector);
            const dropId = $(this).attr("id");
            const label = $dragElement.find("input").val();
            
            // Configure dragged element
            $dragElement.find("input").prop('disabled', true)
                .end().find('.removeChairTTransportationType').show()
                .end().find('.layoutDropSeat').text(dropId).hide()
                .end().css('visibility', 'hidden');
            
            // Clone to drop area
            const cloneType = isLabel ? "labelDropTTransportationType" : "chairTTransportationType";
            const cloneContent = $dragElement.html().replace(`value="${number}"`, `value="${label}"`);
            $(this).find(".layoutDisplaySeat").html(
                `<div class="${cloneType}" style="padding: 0; margin: 0; border: none;">${cloneContent}</div>`
            );
            
            // Set drop area data
            $(this).find("span.BoatNumber").text(number).hide()
                .end().find("span.BoatLabel").text(label).hide()
                .end().droppable('disable');
            
            // Remove selection highlights
            $('#layout-grids-container').find("td").removeClass("layoutSelectTTransportationType");
            
            eventRemoveDragPosition();
        }
        
        function generateDragTTransportationType(element, number){
            $(element).draggable('destroy').data('number', number).draggable({
                containment: '#layoutContainerTTransportationType',
                cursor: 'move',
                revert: 'invalid',
                helper: 'clone',
                zIndex: 1000,
                start: function() {
                    $(this).css('z-index', 1001);
                },
                stop: function() {
                    $(this).css('z-index', '');
                }
            });
        }
        
        function generateDropTTransportationType(element){
            $(element).droppable('destroy').droppable({
                accept: '.chairTTransportationType, .labelDropTTransportationType',
                hoverClass: 'hovered',
                tolerance: 'pointer',
                drop: handleChairTTransportationTypeDrop
            });
        }
        
        function generateDefaultTableTTransportationType(){
            const totalRow = Math.min(replaceNum($("#layoutDropTTransportationTypeRow").val()), MAX_ROWS_COLS);
            const totalColumn = Math.min(replaceNum($("#layoutDropTTransportationTypeColumn").val()), MAX_ROWS_COLS);
            const totalLevels = Math.max(1, replaceNum($('#TTransportationTypeTotalLevels').val()) || 1);

            $('#level-tabs-container').empty();
            $('#layout-grids-container').empty();
            
            for(let l = 1; l <= totalLevels; l++) {
                const levelName = `Level ${l}`;
                $('#level-tabs-container').append(`<a href="#" class="level-tab" data-level="${l}"><input type="text" class="level-name-input" value="${levelName}"></a>`);
                $('#layout-grids-container').append(`<div id="layout-grid-level-${l}" data-level="${l}" class="layout-grid" style="display: none;"><table cellpadding="2" cellspacing="0" style="margin-top: 10px;" id="layoutDropTTransportationType_level_${l}" class="table_print"></table></div>`);

                const $currentTable = $(`#layoutDropTTransportationType_level_${l}`);
                if(totalRow > 0 && totalColumn > 0){
                    const totalTableWidth = 61 * totalColumn;
                    let tableHtml = '';
                    for (let row = 1; row <= totalRow; row++) {
                        tableHtml += '<tr>';
                        for (let col = 1; col <= totalColumn; col++) {
                            const indexCol = `${row}BT${col}`;
                            tableHtml += createColTTransportationType(`level_${l}_${indexCol}`);
                        }
                        tableHtml += '</tr>';
                    }
                    $currentTable.html(tableHtml);
                    $currentTable.css('width', totalTableWidth);
                    
                    for (let row = 1; row <= totalRow; row++) {
                        for (let col = 1; col <= totalColumn; col++) {
                             generateDropTTransportationType(`#layoutDropTTransportationTypelevel_${l}_${row}BT${col}`);
                        }
                    }
                }
            }

            $('.level-tab').on('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'input') {
                    e.target.focus();
                    return; 
                }
                e.preventDefault();
                switchLevel($(this).data('level'));
            });

            if(totalLevels > 0){
                 switchLevel(1);
            }
           
            loadEventLayoutTTransportationType();
        }
        
        function generateSeatTTransportationType(){
            const totalSeat = Math.min(replaceNum($("#TTransportationTypeNumberOfSeat").val()), MAX_SEATS);
            $seatContainer.empty();
            
            if(totalSeat > 0){
                for (let i = 1; i <= totalSeat; i++) {
                    const seatLayout = `
                        <div class="chairTTransportationType" id="chairTTransportationType${i}">
                            <div class="chairLabel">
                                <span class="layoutDropSeat"></span>
                                <input type="text" style="width: 50%; height: 10px; font-size: 11px;" value="${i}" />
                            </div>
                            <div class="chairImg"><span style="display: none;">${i}</span></div>
                            <div class="chairRemove">
                                <img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" />
                            </div>
                        </div>`;
                    $seatContainer.append(seatLayout);
                    generateDragTTransportationType(`#chairTTransportationType${i}`, i);
                }
                
                $(".chairLabel input").off('blur').on('blur', function(){
                    const number = $(this).closest(".chairTTransportationType").find(".chairImg span").text();
                    if($(this).val() === ""){
                        $(this).val(number);
                    }
                });
            }
        }
        
        function loadEventLayoutTTransportationType(){
            $('#layout-grids-container td').off('click').on('click',function(){
                const $this = $(this);
                const isChecked = $this.find("b").text() === '1';
                const hasChair  = $this.find("span.BoatNumber").text() !== '';
                
                if(isChecked || hasChair){
                    $this.find("b").text('');
                    $this.removeClass("layoutSelectTTransportationType");
                } else {
                    $this.find("b").text('1');
                    $this.addClass("layoutSelectTTransportationType");
                }
            });
        }

        function switchLevel(level) {
            currentLevel = level;
            $('.level-tab').removeClass('active');
            $(`.level-tab[data-level=${level}]`).addClass('active');
            $('.layout-grid').hide();
            $(`#layout-grid-level-${level}`).show();
        }
        
        function addLevel(event) {
            if (event) event.preventDefault();
            
            let newLevelNum = $('.level-tab').length + 1;
            $('#TTransportationTypeTotalLevels').val(newLevelNum);
            
            const levelName = `Level ${newLevelNum}`;
            $('#level-tabs-container').append(`<a href="#" class="level-tab" data-level="${newLevelNum}"><input type="text" class="level-name-input" value="${levelName}"></a>`);
            $('#layout-grids-container').append(`<div id="layout-grid-level-${newLevelNum}" data-level="${newLevelNum}" class="layout-grid" style="display: none;"><table cellpadding="2" cellspacing="0" style="margin-top: 10px;" id="layoutDropTTransportationType_level_${newLevelNum}" class="table_print"></table></div>`);

            const newTab = $(`.level-tab[data-level=${newLevelNum}]`);
            newTab.on('click', function(e) {
                 if (e.target.tagName.toLowerCase() === 'input') { e.target.focus(); return; }
                e.preventDefault();
                switchLevel($(this).data('level'));
            });
            
            const totalRow = Math.min(replaceNum($("#layoutDropTTransportationTypeRow").val()), MAX_ROWS_COLS);
            const totalColumn = Math.min(replaceNum($("#layoutDropTTransportationTypeColumn").val()), MAX_ROWS_COLS);
            const $newTable = $(`#layoutDropTTransportationType_level_${newLevelNum}`);
            if (totalRow > 0 && totalColumn > 0) {
                let tableHtml = '';
                for (let row = 1; row <= totalRow; row++) {
                    tableHtml += '<tr>';
                    for (let col = 1; col <= totalColumn; col++) {
                        tableHtml += createColTTransportationType(`level_${newLevelNum}_${row}BT${col}`);
                    }
                    tableHtml += '</tr>';
                }
                $newTable.html(tableHtml);
                $newTable.css('width', 61 * totalColumn);

                for (let row = 1; row <= totalRow; row++) {
                    for (let col = 1; col <= totalColumn; col++) {
                        generateDropTTransportationType(`#layoutDropTTransportationTypelevel_${newLevelNum}_${row}BT${col}`);
                    }
                }
            }
            loadEventLayoutTTransportationType();
            switchLevel(newLevelNum);
        }
        
        function removeLevel(event) {
            if (event) event.preventDefault();
            
            if (currentLevel === 1 && $('.level-tab').length > 1) {
                showDialog("The first level cannot be removed while other levels exist.", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }
            if ($('.level-tab').length <= 1) {
                showDialog("Cannot remove the last level.", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }

            showConfirmDialog('Are you sure you want to remove this level? All seats on this level will be unassigned.', '<?php echo DIALOG_CONFIRMATION; ?>', function() {
                const $levelToRemoveGrid = $(`#layout-grid-level-${currentLevel}`);

                $levelToRemoveGrid.find('td').each(function() {
                    const $cell = $(this);
                    const boatNumber = $cell.find("span.BoatNumber").text();
                    if (boatNumber) {
                        const isLabel = isNaN(boatNumber);
                        const dragSelector = isLabel ? `#labelDropTTransportationType${boatNumber}` : `#chairTTransportationType${boatNumber}`;
                        $(dragSelector).removeAttr("style")
                            .find("input").prop('disabled', false)
                            .end().find('.removeChairTTransportationType').hide()
                            .end().find('.layoutDropSeat').text('')
                            .end().css('visibility', 'visible');
                        generateDragTTransportationType(dragSelector, boatNumber);
                    }
                });

                $(`.level-tab[data-level=${currentLevel}]`).remove();
                $levelToRemoveGrid.remove();

                let levelCounter = 1;
                $('.level-tab').each(function() {
                    const $tab = $(this);
                    const oldLevel = $tab.data('level');
                    const $grid = $(`#layout-grid-level-${oldLevel}`);
                    
                    if (levelCounter !== oldLevel) {
                        $tab.data('level', levelCounter).attr('data-level', levelCounter);
                        $grid.attr('id', `layout-grid-level-${levelCounter}`).data('level', levelCounter);
                        const $table = $grid.find('table');
                        $table.attr('id', `layoutDropTTransportationType_level_${levelCounter}`);
                        $table.find('td').each(function() {
                           let currentId = $(this).attr('id');
                           if (currentId) {
                              let newId = currentId.replace(`level_${oldLevel}_`, `level_${levelCounter}_`);
                              $(this).attr('id', newId);
                           }
                        });
                    }
                    levelCounter++;
                });
                
                $('#TTransportationTypeTotalLevels').val($('.level-tab').length);
                switchLevel(1);
            });
        }
        
        function rowPlusTTransportationType(event){
            if(event) event.preventDefault();
            
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            const totalRow = Math.min($currentTable.find("tr").length + 1, MAX_ROWS_COLS);
            let totalColumn = 0;
            
            $currentTable.find("tr:first td").each(function(){
                totalColumn += replaceNum($(this).attr('colspan')) || 1;
            });
            
            if(totalRow > 0 && totalColumn > 0){
                let tableRow = '<tr>';
                
                for (let col = 1; col <= totalColumn; col++) {
                    const indexCol = `${totalRow}BT${col}`;
                    tableRow += createColTTransportationType(`level_${currentLevel}_${indexCol}`);
                }
                
                tableRow += '</tr>';
                $currentTable.append(tableRow);

                 for (let col = 1; col <= totalColumn; col++) {
                    const indexCol = `${totalRow}BT${col}`;
                    generateDropTTransportationType(`#layoutDropTTransportationTypelevel_${currentLevel}_${indexCol}`);
                }
                
                loadEventLayoutTTransportationType();
                $("#layoutDropTTransportationTypeRow").val(totalRow);
            }
        }
        
        function rowCutTTransportationType(event){
            if(event) event.preventDefault();
            
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            const totalRow = $currentTable.find("tr").length;
            
            if(totalRow > 1){
                $currentTable.find("tr:last").remove();
                $("#layoutDropTTransportationTypeRow").val(totalRow - 1);
            } else {
                showDialog("Cannot remove the last row", "<?php echo DIALOG_INFORMATION; ?>");
            }
        }
        
        function colPlusTTransportationType(event){
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            
            let totalColumn = 0;
            $currentTable.find("tr:first td").each(function(){
                totalColumn += replaceNum($(this).attr('colspan')) || 1;
            });
            totalColumn = Math.min(totalColumn + 1, MAX_ROWS_COLS);
            
            if($currentTable.find("tr").length > 0 && totalColumn > 0){
                $currentTable.find("tr").each(function(index){
                    const rowIndex = index + 1;
                    const indexCol = `${rowIndex}BT${totalColumn}`;
                    const col = createColTTransportationType(`level_${currentLevel}_${indexCol}`);
                    
                    $(this).find("td:last").after(col);
                    generateDropTTransportationType(`#layoutDropTTransportationTypelevel_${currentLevel}_${indexCol}`);
                });
                
                $currentTable.css('width', 61 * totalColumn);
                $("#layoutDropTTransportationTypeColumn").val(totalColumn);
                loadEventLayoutTTransportationType();
            }
        }
        
        function colCutTTransportationType(event){
            if(event) event.preventDefault();
            
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            let totalColumn = 0;
            $currentTable.find("tr:first td").each(function(){
                totalColumn += replaceNum($(this).attr('colspan')) || 1;
            });
            
            if($currentTable.find("tr").length > 0 && totalColumn > 1){
                $currentTable.find("tr").each(function(){
                    const $lastTd = $(this).find("td:last");
                    
                    if(replaceNum($lastTd.attr('colspan')) > 1){
                        $lastTd.find('b').text('1');
                        unMergeTTransportationType();
                    }
                    $lastTd.remove();
                });
                
                totalColumn--;
                $currentTable.css('width', totalColumn > 0 ? 61 * totalColumn : 0);
                $("#layoutDropTTransportationTypeColumn").val(totalColumn);
            } else {
                showDialog("Cannot remove the last column", "<?php echo DIALOG_INFORMATION; ?>");
            }
        }
        
        function createColTTransportationType(indexCol){
            return `<td style="width: 60px; height: 86px; padding: 0; margin: 0; background: #f5f5f5; border:1px solid #fff;" 
                     id="layoutDropTTransportationType${indexCol}">
                      <span class="BoatNumber"></span>
                      <span class="BoatLabel"></span>
                      <b style="display: none;"></b>
                      <div class="layoutDisplaySeat"></div>
                    </td>`;
        }
        
        function mergeTTransportationType(event){
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            let cellsToMerge = $currentTable.find("td.layoutSelectTTransportationType");
            
            if(cellsToMerge.length < 2) {
                showDialog("Please select at least 2 adjacent cells to merge.", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }
            
            // ... [Simplified merging logic as before]
            
            $currentTable.find("td").removeClass("layoutSelectTTransportationType").find("b").text('');
        }
        
        function unMergeTTransportationType(event){
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            
            $currentTable.find("td.layoutSelectTTransportationType").each(function(){
               // ... [Simplified unmerging logic as before]
            });
            generateDefaultTableTTransportationType();
        }
        
        function convertLayoutToJsonTTransportationType(){
            const totalRows = replaceNum($("#layoutDropTTransportationTypeRow").val());
            let totalColumns = replaceNum($("#layoutDropTTransportationTypeColumn").val());
            const levelNames = [];
            $('#level-tabs-container .level-name-input').each(function() {
                levelNames.push($(this).val());
            });

            const layoutData = {
                rows: totalRows,
                columns: totalColumns,
                total_levels: replaceNum($('#TTransportationTypeTotalLevels').val()) || 1,
                level_names: levelNames,
                seats: [],
                version: "2.3",
                generatedAt: new Date().toISOString()
            };
            
            $('.layout-grid').each(function() {
                const level = $(this).data('level');
                const $table = $(this).find('table');
                
                $table.find("tr").each(function(rowIndex){
                    let colCount = 0;
                    $(this).find("td").each(function(){
                        const $cell = $(this);
                        const colspan = parseInt($cell.attr("colspan")) || 1;
                        const rowspan = parseInt($cell.attr("rowspan")) || 1;
                        const value = $cell.find("span.BoatNumber").text();
                        const label = $cell.find("span.BoatLabel").text();
                        
                        if(value || (label && label.length > 0)){
                            layoutData.seats.push({
                                row: rowIndex + 1,
                                column: colCount + 1,
                                colspan: colspan,
                                rowspan: rowspan,
                                value: value,
                                label: label,
                                level: level
                            });
                        }
                        
                        colCount += colspan;
                    });
                });
            });
            
            return JSON.stringify(layoutData);
        }
        
        function initializeImageUploads(){
            // Main image upload
            $("#TTransportationFormUploadImage").ajaxForm({
                dataType: "json",
                beforeSerialize: validateImageUpload,
                beforeSend: showLoadingSpinner,
                success: function(result) {
                    hideLoadingSpinner();
                    $("#TTransportationTypePhoto").val(result.img);
                    $("#TTransportationTypePhotoDisplay").attr("src", "<?php echo $this->webroot; ?>public/transportation_type/" + result.img);
                }
            });
            
            // Other images upload
            $("#TTransportationFormUploadOtherImage").ajaxForm({
                dataType: "json",
                beforeSerialize: validateImageUpload,
                beforeSend: showLoadingSpinner,
                success: function(result) {
                    hideLoadingSpinner();
                    const index = Math.floor((Math.random() * 100000) + 1);
                    const imgDiv = `
                        <li id="sortPhoto${index}">
                            <div style="float: left; width: 100px; margin-left: 3px; margin-bottom: 3px;">
                                <img class="btnDeleteTransportationTypeOtherImg" src="<?php echo $this->webroot; ?>img/button/delete.png" style="z-index: 99999; width: 18px; cursor: pointer;"/>
                                <img src="<?php echo $this->webroot; ?>public/transportation_type/${result.img}" style="width: 100px; height: 65px;" />
                                <input type="hidden" value="<?php echo $link; ?>" name="data[photo_path_other][]" class="otherImgPathValue" />
                                <input type="hidden" value="${result.img}" name="data[photo_other][]" class="otherImgValue" />
                            </div>
                        </li>`;
                    $("#sortablePhoto").append(imgDiv);
                    deleteOtherImageTTransportationType();
                }
            });
            
            // File input change handlers
            $("#TTransportationTypeUpload").on("change", function(event) {
                document.getElementById('TTransportationTypePhotoUpload').files = event.target.files;
                $("#TTransportationFormUploadImage").submit();
            });
            
            $("#TTransportationTypeUploadOtherImage").on("change", function(event) {
                document.getElementById('TTransportationTypePhotoUploadOther').files = event.target.files;
                $("#TTransportationFormUploadOtherImage").submit();
            });
            
            // Sortable for other images
            $("#sortablePhoto").sortable({ revert: true });
        }
        
        function validateImageUpload($form, options) {
            const extArray = [".bmp",".jpg",".gif",".tif",".png"];
            
            const fileInput = $form.find("input[type=file]")[0];
            if (!fileInput.files.length) return false;
            
            const file = fileInput.files[0].name;
            const ext = file.slice(file.lastIndexOf(".")).toLowerCase();
            
            if (!extArray.includes(ext)){
                showDialog(`Please only upload files that end in types: <b>${extArray.join("  ")}</b>. Please select a new file to upload again.`, '<?php echo DIALOG_INFORMATION; ?>');
                return false;
            }
            return true;
        }
        
        function showLoadingSpinner() {
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
        }
        
        function hideLoadingSpinner() {
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
        }
        
        function deleteOtherImageTTransportationType(){
            $(".btnDeleteTransportationTypeOtherImg").off("click").on("click", function(){
                const $listItem = $(this).closest("li");
                
                showConfirmDialog('Do you want to delete image?', '<?php echo DIALOG_CONFIRMATION; ?>', function() {
                     $listItem.remove();
                });
            });
        }

        // Set Form Container Height
        var windowHeight  = $(window).height();
        var headerHeight  = $('.ui-layout-north').outerHeight(true);
        var tabsNavHeight = $('.ui-tabs-nav').outerHeight(true);
        var formHeight    = $('#tTransportationTypeFormHeader').outerHeight(true);
        var formContainer = windowHeight - headerHeight - tabsNavHeight - formHeight - 100;
        $('#tTransportationTypeFormContainer').css({
            'height': formContainer + 'px',
            'max-height': formContainer + 'px'
        });
    });
</script>
<div class="filter-container" id="tTransportationTypeFormHeader">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTTransportationType">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
        <div class="filter-form-group">
            <button type="button" class="form-btn btnSaveTTransportationType">
                <i class="fas fa-save"></i>
                <span class="txtSaveTTransportationType"><?php echo ACTION_SAVE; ?></span>
            </button>
        </div>
    </div>
</div>
<form id="TTransportationFormUploadImage" action="<?php echo $this->base; ?>/t_transportation_types/upload" method="post" enctype="multipart/form-data">
    <table style="display: none;">
        <tr>
            <td>
                <input type="file" name="photo" id="TTransportationTypePhotoUpload" />
            </td>
        </tr>
    </table>
</form>
<form id="TTransportationFormUploadOtherImage" action="<?php echo $this->base; ?>/t_transportation_types/upload" method="post" enctype="multipart/form-data">
    <table style="display: none;">
        <tr>
            <td>
                <input type="file" name="photo" id="TTransportationTypePhotoUploadOther" />
            </td>
        </tr>
    </table>
</form>
<?php 
echo $this->Form->create('TTransportationType', array('id' => 'TTransportationTypeAddForm', 'class' => 'form-table-container')); 
?>
<div class="form-container" id="tTransportationTypeFormContainer">
    <input type="hidden" name="data[TTransportationType][photo_path]" value="<?php echo $link; ?>" />
    <input type="hidden" name="data[TTransportationType][layout]" id="TTransportationTypeLayout" />
    <fieldset>
        <legend><?php __(MENU_TRANSPORTATION_TYPE_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell" rowspan="3">
                    <table>
                        <tr>
                            <td>
                                <input type="hidden" name="data[TTransportationType][photo]" id="TTransportationTypePhoto" />
                                <img alt="" id="TTransportationTypePhotoDisplay" style="width: 150px; height: 100px;" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Size: 720 * 480 Px
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <input type="file" id="TTransportationTypeUpload" />
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="form-label-cell"><label for="TTransportationTypeName"><?php echo TABLE_NAME; ?> <span class="red">*</span> :</label></td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('name', array('class'=>'form-input validate[required]')); ?>
                    </div>
                </td>
                <td class="form-label-cell" style="vertical-align: top;" rowspan="3"><label for="TTransportationTypeAmenityId"><?php echo MENU_AMENITY; ?> :</label></td>
                <td class="form-input-cell" rowspan="3" style="vertical-align: top;">
                    <div class="form-input-container">
                        <?php echo $this->Form->input('amenity_id', array('name' => 'data[amenity_id]', 'class' => 'form-select-multi', 'multiple' => true, 'div' => false, 'label' => false)); ?>
                    </div>
                </td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell"><label for="TTransportationTypeNumberOfSeat"><?php echo TABLE_SEAT_NUMBER; ?> <span class="red">*</span> :</label></td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <?php echo $this->Form->text('number_of_seat', array('class'=>'form-input validate[required] integer')); ?>
                    </div>
                </td>
            </tr>
            <tr class="form-row">
                <td class="form-label-cell"><label for="TTransportationTypeSeatType"><?php echo "Seat Type"; ?> <span class="red">*</span> :</label></td>
                <td class="form-input-cell">
                    <div class="form-input-container">
                        <select name="data[TTransportationType][seat_type]" id="TTransportationTypeSeatType" class="form-select validate[required]">
                            <option value=""><?php echo INPUT_SELECT; ?></option>
                            <option value="1"><?php echo "Sitting"; ?></option>
                            <option value="2"><?php echo "Sleeping"; ?></option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    <br />
    <fieldset id="layoutContainerTTransportationType">
        <legend>Layout</legend>
        <div class="boxLeftTTransportationType" id="layoutSeatTTransportationType"></div>
        <div class="boxRightTTransportationType">
            <table class="form-table">
                <tr class="form-row" style="border: none;">
                    <td class="form-label-cell">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 15%;">Total Row:</td>
                                <td style="width: 18%;"><input type="text" id="layoutDropTTransportationTypeRow" name="data[TTransportationType][total_row]" style="width: 90%;" class="integer" /></td>
                                <td style="width: 18%;">Total Column:</td>
                                <td style="width: 18%;"><input type="text" id="layoutDropTTransportationTypeColumn" name="data[TTransportationType][total_column]" style="width: 90%;" class="integer" /></td>
                                <td style="width: 15%;">Total Levels:</td>
                                <td style="width: 10%;"><input type="text" id="TTransportationTypeTotalLevels" class="integer" value="1" style="width: 90%;"/></td>
                            </tr>
                        </table>
                    </td>
                    <td class="form-label-cell">
                        <div class="buttons">
                            <a href="#" class="positive btnGenerateLayoutDropTTransportationType btnLayoutTooltip">
                                <i class="fas fa-sync-alt"></i>
                                <span class="btnLayoutTooltiptext">Generate</span>
                            </a>
                        </div>
                         <div class="buttons">
                            <a href="#" class="positive btnAddLevel btnLayoutTooltip">
                                <i class="fas fa-layer-group"></i>
                                <span class="btnLayoutTooltiptext">Add Level</span>
                            </a>
                        </div>
                        <div class="buttons">
                            <a href="#" class="positive btnRemoveLevel btnLayoutTooltip">
                                <i class="fas fa-minus-square"></i>
                                <span class="btnLayoutTooltiptext">Remove Level</span>
                            </a>
                        </div>
                        <div class="buttons">
                            <a href="#" class="positive btnMergeLayoutDropTTransportationType btnLayoutTooltip">
                                <i class="fas fa-object-group"></i>
                                <span class="btnLayoutTooltiptext">Merge Columns</span>
                            </a>
                        </div>
                        <div class="buttons">
                            <a href="#" class="positive btnUnmergeLayoutDropTTransportationType btnLayoutTooltip">
                                <i class="fas fa-object-ungroup"></i>
                                <span class="btnLayoutTooltiptext">Unmerge Columns</span>
                            </a>
                        </div>
                        <div style="clear: both;"></div>
                    </td>
                </tr>
                <tr class="form-row">
                    <td class="form-label-cell" colspan="2">
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeCapitain">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Capitain" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">Capitain</span>Capitain</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeToilet">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Toilet" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">Toilet</span>Toilet</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <!-- <div class="labelDropTTransportationType" id="labelDropTTransportationTypeOpen1">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Open Air Seat" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">Open1</span>Open Air Seat</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div> -->
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeHostess">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Hostess" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">Hostess</span>Hostess</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeDownStair">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Down Stair" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">DownStair</span>Down Stair</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeUpStair">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Up Stair" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">UpStair</span>Up Stair</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeDoor">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="Door" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">Door</span>Door</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <div style="clear: both;"></div>
                    </td>
                </tr>
            </table>
            <!-- Level UI -->
            <div id="level-tabs-container" style="border-bottom: 1px solid #ccc; margin-top: 15px;"></div>
            <div id="layout-grids-container" style="overflow-x: auto; padding-top: 10px;"></div>
        </div>
        <div style="clear: both;"></div>
        <br/>
        <div class="boxLeftTTransportationType">
            <table style="width: 100%;" cellpadding="3">
                <tr>
                    <td>Upload Other Image for Slide (Size: 720 * 480 Px)</td>
                </tr>
                <tr>
                    <td style="border-bottom: 2px solid #000; padding-bottom: 15px;">
                        <input type="file" id="TTransportationTypeUploadOtherImage" />
                    </td>
                </tr>
                <tr>
                    <td valign="top"><ul id="sortablePhoto"></ul></td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    <br />
    <div style="clear: both;"></div>
</div>
<?php echo $this->Form->end(); ?>