<?php 
echo $this->element('prevent_multiple_submit'); 
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$link = $protocol . "://" . $_SERVER['HTTP_HOST'] . str_replace("/t_transportation_types/edit/","",$_SERVER['REQUEST_URI']);
?>
<style type="text/css" media="screen">
    .labelDropTTransportationType {
        width: 100px;
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
        border: 1px dashed #0066cc;
    }
    
    .ui-draggable-dragging {
        opacity: 0.8;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        transform: scale(1.05);
    }
    
    .layoutDropArea {
        width: 60px; 
        height: 86px; 
        padding: 0; 
        margin: 0; 
        background: #f5f5f5; 
        border:1px solid #fff;
        position: relative;
    }
    
    .layoutDisplaySeat {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
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
        const SEAT_WIDTH = 60;
        const SEAT_HEIGHT = 86;
        
        // Cache DOM elements
        const $layoutContainer = $('#layoutContainerTTransportationType');
        let currentLevel = 1;
        const $levelTabsContainer = $('#level-tabs-container');
        const $layoutGridsContainer = $('#layout-grids-container');
        const $seatContainer = $('#layoutSeatTTransportationType');
        const $form = $("#TTransportationTypeEditForm");
        
        // Initialize the editor
        initTransportationTypeEditor();

        // Core initialization function
        function initTransportationTypeEditor() {
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
                beforeSerialize: prepareFormData,
                beforeSubmit: showLoadingState,
                success: handleFormSubmission
            });
            
            // Initialize drag and drop
            initializeDragDrop();
            
            // Set up event handlers
            setupEventHandlers();
            
            // Initialize image uploads
            initializeImageUploads();
            
            // Add touch support for mobile devices
            addTouchSupport();

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
        }

        function initializeDragDrop() {
            resetDragElements(); 
            generateSeats();
            generateLayoutGrid();
            
            if($("#TTransportationTypeLayout").val()) {
                console.log("Loading saved layout...");
                loadSavedLayout();
            }
        }

        function resetDragElements() {
            const dragElements = [
                {id: '#labelDropTTransportationTypeCapitain', label: 'Capitain'},
                {id: '#labelDropTTransportationTypeToilet', label: 'Toilet'},
                {id: '#labelDropTTransportationTypeHostess', label: 'Hostess'},
                {id: '#labelDropTTransportationTypeDownStair', label: 'DownStair'},
                {id: '#labelDropTTransportationTypeUpStair', label: 'UpStair'},
                {id: '#labelDropTTransportationTypeDoor', label: 'Door'}
            ];
            
            dragElements.forEach(item => {
                $(item.id)
                    .removeAttr("style")
                    .css('background', 'none')
                    .data('number', item.label)
                    .draggable({
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
            });
            
            setupRemoveHandlers();
        }

        function generateSeats() {
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
                
                $(".chairLabel input").off('blur').blur(function(){
                    const number = $(this).closest(".chairTTransportationType").find(".chairImg span").text();
                    if($(this).val() === ""){
                        $(this).val(number);
                    }
                });
            }
        }

        function initLevels() {
            const layoutData = $("#TTransportationTypeLayout").val() ? $.parseJSON($("#TTransportationTypeLayout").val()) : {};
            const totalLevels = layoutData.total_levels || parseInt($('#TTransportationTypeTotalLevels').val().replace(/,/g,"")) || 1;
            
            $('#TTransportationTypeTotalLevels').val(totalLevels);
            $levelTabsContainer.empty();
            $layoutGridsContainer.empty();

            for (let i = 1; i <= totalLevels; i++) {
                const levelName = layoutData.level_names ? (layoutData.level_names[i-1] || `Level ${i}`) : `Level ${i}`;
                const tab = $(`<a href="#" class="level-tab" data-level="${i}"><input type="text" class="level-name-input" value="${levelName}"></a>`);
                const gridContainer = $(`<div id="layout-grid-level-${i}" data-level="${i}" class="layout-grid" style="display: none;"><table cellpadding="2" cellspacing="0" style="margin-top: 10px;" id="layoutDropTTransportationType_level_${i}" class="table_print"></table></div>`);

                $levelTabsContainer.append(tab);
                $layoutGridsContainer.append(gridContainer);

                tab.on('click', function(e){
                    if (e.target.tagName.toLowerCase() === 'input') {
                        e.target.focus();
                        return; 
                    }
                    e.preventDefault();
                    switchLevel(i);
                });
            }
            if(totalLevels > 0){
                switchLevel(1);
            }
        }

        function switchLevel(level){
            currentLevel = level;
            $levelTabsContainer.find('.level-tab').removeClass('active');
            $levelTabsContainer.find(`.level-tab[data-level=${level}]`).addClass('active');
            $('.layout-grid').hide();
            $(`#layout-grid-level-${level}`).show();
        }

        function generateLayoutGrid() {
            initLevels();
            const totalRows = Math.min(replaceNum($("#layoutDropTTransportationTypeRow").val()), MAX_ROWS_COLS);
            const totalColumns = Math.min(replaceNum($("#layoutDropTTransportationTypeColumn").val()), MAX_ROWS_COLS);
            
            $('table[id^="layoutDropTTransportationType_level_"]').empty();
            
            if(totalRows > 0 && totalColumns > 0) {
                const tableWidth = SEAT_WIDTH * totalColumns;
                $('table[id^="layoutDropTTransportationType_level_"]').each(function() {
                    const $currentTable = $(this);
                    for (let row = 1; row <= totalRows; row++) {
                        let rowHtml = '<tr>';
                        for (let col = 1; col <= totalColumns; col++) {
                            const cellId = `layoutDropTTransportationType${row}BT${col}`;
                            rowHtml += `
                            <td class="layoutDropArea" id="${cellId}" style="width: ${SEAT_WIDTH}px; height: ${SEAT_HEIGHT}px;">
                                <span class="BoatNumber"></span>
                                <span class="BoatLabel"></span>
                                <b style="display: none;"></b>
                                <div class="layoutDisplaySeat"></div>
                            </td>`;
                    }
                    rowHtml += '</tr>';
                    $currentTable.append(rowHtml);
                    }
                     $currentTable.css('width', tableWidth);
                });
                
                initializeDropAreas();
                setupLayoutClickHandlers();
            }
        }

        function initializeDropAreas() {
            $layoutGridsContainer.find("td.layoutDropArea").each(function() {
                const $cell = $(this);
                $cell.droppable({
                    accept: '.chairTTransportationType, .labelDropTTransportationType',
                    hoverClass: 'hovered',
                    tolerance: 'pointer',
                    drop: function(event, ui) {
                        handleDropEvent($(this), ui.draggable);
                    }
                });
            });
        }

        function handleDropEvent($dropArea, $draggedItem) {
            const number = $draggedItem.data('number');
            const isLabel = isNaN(number);
            const dragSelector = isLabel ? `#labelDropTTransportationType${number}` : `#chairTTransportationType${number}`;
            const $dragElement = $(dragSelector);
            const dropId = $dropArea.attr("id");
            const label = $dragElement.find("input").val();
            
            // Configure dragged element
            $dragElement.find("input").prop('disabled', true)
                .end().find('.removeChairTTransportationType').show()
                .end().find('.layoutDropSeat').text(dropId).hide()
                .end().css('visibility', 'hidden');
            
            // Clone to drop area
            const cloneType = isLabel ? "labelDropTTransportationType" : "chairTTransportationType";
            const cloneContent = $dragElement.html().replace(`value="${number}"`, `value="${label}"`);
            $dropArea.find(".layoutDisplaySeat").html(
                `<div class="${cloneType}" style="padding: 0; margin: 0; border: none;">${cloneContent}</div>`
            );
            
            // Set drop area data
            $dropArea.find("span.BoatNumber").text(number).hide()
                .end().find("span.BoatLabel").text(label).hide()
                .end().droppable('disable');
            
            setupRemoveHandlers();
            $layoutGridsContainer.find("td").removeClass("layoutSelectTTransportationType");
        }
        
        function loadSavedLayout() {
            try {
                const layoutJSON = '<?php echo $this->data['TTransportationType']['layout']; ?>';
                if (!layoutJSON) {
                    generateLayout();
                    return;
                }
                const layoutData = $.parseJSON(layoutJSON);
                if (!layoutData.seats) {
                    console.error("Missing 'seats' property in layoutData");
                    generateLayout();
                    return;
                }

                generateLayoutGrid(); 
                console.log("Loading layout from JSON:", layoutData);
                const spanAdjustments = {};

                layoutData.seats.forEach(seat => {
                    const level = seat.level || 1;
                    if (!spanAdjustments[level]) spanAdjustments[level] = {};
                    if (!spanAdjustments[level][seat.row]) spanAdjustments[level][seat.row] = [];
                    
                    if (seat.colspan > 1 || seat.rowspan > 1) {
                        spanAdjustments[level][seat.row].push({ column: seat.column, colspan: seat.colspan, rowspan: seat.rowspan });
                    }
                });

                Object.keys(spanAdjustments).forEach(level => {
                     Object.keys(spanAdjustments[level]).forEach(row => {
                        const $rowEl = $(`#layoutDropTTransportationType_level_${level}`).find(`tr:eq(${row - 1})`);
                        
                        spanAdjustments[level][row].sort((a, b) => b.column - a.column).forEach(span => {
                            const $cell = $rowEl.find(`td:eq(${span.column - 1})`);
                            $cell.attr('colspan', span.colspan).attr('rowspan', span.rowspan);
                            for (let i = 1; i < span.colspan; i++) $cell.next().remove();
                            for (let i = 1; i < span.rowspan; i++) {
                                 $(`#layoutDropTTransportationType_level_${level}`).find(`tr:eq(${(row - 1) + i})`).find(`td:eq(${span.column - 1})`).remove();
                            }
                        });
                    });
                });

                layoutData.seats.forEach(seat => {
                    const level = seat.level || 1;
                    if (!seat.value && !seat.label) return;
                    
                    const $table = $(`#layoutDropTTransportationType_level_${level}`);
                    const $rowEl = $table.find(`tr:eq(${seat.row - 1})`);
                    
                    let physicalColIdx = seat.column - 1;
                    if ($rowEl) {
                        for (let i = 0; i < seat.column - 1; i++) {
                             let colspan = parseInt($rowEl.find(`td:eq(${i})`).attr('colspan'));
                             if (colspan > 1) physicalColIdx -= (colspan - 1);
                        }
                    }
                    const $cell = $rowEl.find(`td:eq(${physicalColIdx})`);

                    if ($cell.length) {
                        const dragSelector = isNaN(seat.value) ? `#labelDropTTransportationType${seat.value}` : `#chairTTransportationType${seat.value}`;
                        if ($(dragSelector).length) {
                            placeItemInCell(dragSelector, $cell, seat.label || seat.value, `level_${level}_${seat.row}BT${seat.column}`);
                        }
                    }
                });

            } catch (e) {
                console.error("Error loading layout:", e);
                showDialog("Error loading saved layout. Please reconfigure manually.", "<?php echo DIALOG_INFORMATION; ?>");
            }
        }

        function placeItemInCell(dragSelector, $cell, label, cellId) {
            const $dragItem = $(dragSelector);
            const number = $dragItem.data('number');
            const isLabel = isNaN(number);
            const cloneClass = isLabel ? 'labelDropTTransportationType' : 'chairTTransportationType';

            $dragItem.css('visibility', 'hidden')
                .find('input').prop('disabled', true).val(label)
                .end().find('.removeChairTTransportationType').show()
                .end().find('.layoutDropSeat').text(cellId).hide();

            $cell.attr('id', `layoutDropTTransportationType${cellId}`);

            $cell.find('.layoutDisplaySeat').html(`
                <div class="${cloneClass}" style="padding:0;margin:0;border:none">
                    ${$dragItem.html().replace(`value="${number}"`, `value="${label}"`)}
                </div>
            `);
            $cell.find('span.BoatNumber').text(number).hide()
                .end().find('span.BoatLabel').text(label).hide()
                .end().droppable('disable');

            setupRemoveHandlers();
        }

        function setupRemoveHandlers() {
            $(".removeChairTTransportationType").off('click').on('click', function() {
                const $chair = $(this).closest(".chairTTransportationType, .labelDropTTransportationType");
                const dragNum = $chair.find(".chairImg span").text();
                const columDrop = "#" + $chair.find(".layoutDropSeat").text();
                
                const isLabel = isNaN(dragNum);
                const chairDrag = isLabel ? `#labelDropTTransportationType${dragNum}` : `#chairTTransportationType${dragNum}`;
                
                $(chairDrag).removeAttr("style")
                    .find("input").prop('disabled', false)
                    .end().find('.removeChairTTransportationType').hide()
                    .end().find('.layoutDropSeat').text('')
                    .end().css('visibility', 'visible');
                
                $(columDrop).find("span.BoatNumber, span.BoatLabel").text('')
                    .end().find(".layoutDisplaySeat").empty()
                    .end().droppable("enable");
                
                generateDragTTransportationType(chairDrag, dragNum);
            });
        }

        function setupLayoutClickHandlers() {
            $layoutGridsContainer.find("td.layoutDropArea").off('click').on('click', function() {
                const $cell = $(this);
                const isChecked = $cell.find("b").text() === '1';
                const hasChair = $cell.find("span.BoatNumber").text() !== '';
                
                if(isChecked || hasChair) {
                    $cell.find("b").text('');
                    $cell.removeClass("layoutSelectTTransportationType");
                } else {
                    $cell.find("b").text('1');
                    $cell.addClass("layoutSelectTTransportationType");
                }
            });
        }

        function prepareFormData($form, options) {
            $(".integer").each(function(){
                $(this).val($(this).val().replace(/,/g,""));
            });
            $("#TTransportationTypeLayout").val(generateLayoutJSON());
            return true;
        }

        function generateLayoutJSON() {
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
                        
                        if(value || (label && label.length > 0)) {
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

        function showLoadingState() {
            $(".txtSaveTTransportationType").html("<?php echo ACTION_LOADING; ?>");
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
        }

        function handleFormSubmission(result) {
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
            $(".btnBackTTransportationType").click();
            
            createSysAct('Transportation Type', 'Edit', 1, '');
            showDialog(result, '<?php echo DIALOG_INFORMATION; ?>');
        }

        function setupEventHandlers() {
            // Back button
            $(".btnBackTTransportationType").click(handleBackButton);
            
            // Seat number change
            $("#TTransportationTypeNumberOfSeat").on('blur', handleSeatNumberChange);
            
            // Layout generation buttons
            $(".btnGenerateLayoutDropTTransportationType").click(generateLayout);
            $(".btnAddLevel").click(addLevel);
            $(".btnRemoveLevel").click(removeLevel);
            $(".btnRowPlusLayoutDropTTransportationType").click(addRow);
            $(".btnRowCutLayoutDropTTransportationType").click(removeRow);
            $(".btnColPlusLayoutDropTTransportationType").click(addColumn);
            $(".btnColCutLayoutDropTTransportationType").click(removeColumn);
            $(".btnMergeLayoutDropTTransportationType").click(mergeCells);
            $(".btnUnmergeLayoutDropTTransportationType").click(unmergeCells);
        }

        function handleBackButton(event) {
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTTransportationType.fnDraw(false);
            $(this).closest(".rightPanel").hide().empty()
                   .siblings(".leftPanel").show("slide", { direction: "left" }, 500);
        }

        function handleSeatNumberChange() {
            const seatCount = parseInt($(this).val()) || 0;
            if (seatCount < 1 || seatCount > MAX_SEATS) {
                showDialog(`Please enter a valid seat number between 1 and ${MAX_SEATS}`, '<?php echo DIALOG_INFORMATION; ?>');
                return;
            }
            generateSeats();
            generateLayoutGrid();
            resetDragElements();
        }

        function generateLayout(event) {
            if(event) event.preventDefault();  
            const totalRow = Math.min(replaceNum($("#layoutDropTTransportationTypeRow").val()), MAX_ROWS_COLS);
            const totalColumn = Math.min(replaceNum($("#layoutDropTTransportationTypeColumn").val()), MAX_ROWS_COLS);
            
            if(totalRow > 0 && totalColumn > 0) {
                $(this).find('span').text('<?php echo ACTION_LOADING; ?>');
                generateSeats();
                generateLayoutGrid();
                resetDragElements();
                $(this).find('span').text('Generate');
            }
        }
        
        function addLevel(event) {
            if (event) event.preventDefault();
            let currentTotalLevels = parseInt($('#TTransportationTypeTotalLevels').val()) || 0;
            $('#TTransportationTypeTotalLevels').val(currentTotalLevels + 1);
            generateLayoutGrid();
            switchLevel(currentTotalLevels + 1);
        }

        function removeLevel(event) {
            if (event) event.preventDefault();
            if (currentLevel === 1) {
                showDialog("The first level cannot be removed.", "<?php echo DIALOG_INFORMATION; ?>");
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
                         $(dragSelector).removeAttr("style").find("input").prop('disabled', false).end().find('.removeChairTTransportationType').hide().end().find('.layoutDropSeat').text('').end().css('visibility', 'visible');
                         generateDragTTransportationType(dragSelector, boatNumber);
                    }
                });
                
                let totalLevels = parseInt($('#TTransportationTypeTotalLevels').val()) || 1;
                $('#TTransportationTypeTotalLevels').val(totalLevels-1);
                
                generateLayoutGrid(); // This will re-create grids and tabs correctly
                loadSavedLayout();    // Re-load the data into the new structure
                switchLevel(1);
            });
        }
        
        function addRow(event) {
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            const totalRows = $currentTable.find("tr").length;
            if(totalRows >= MAX_ROWS_COLS) {
                showDialog(`Maximum ${MAX_ROWS_COLS} rows allowed`, '<?php echo DIALOG_INFORMATION; ?>');
                return;
            }
            
            const totalColumns = calculateTotalColumns();
            if(totalRows > 0 && totalColumns > 0) {
                let rowHtml = '<tr>';
                for (let col = 1; col <= totalColumns; col++) {
                    rowHtml += createColTTransportationType(``);
                }
                rowHtml += '</tr>';
                $currentTable.append(rowHtml);
                
                $currentTable.find("tr:last td").each(function(index) {
                    const cellId = `layoutDropTTransportationType_level_${currentLevel}_${totalRows + 1}BT${index + 1}`;
                     $(this).attr('id', cellId);
                     $(this).droppable({
                        accept: '.chairTTransportationType, .labelDropTTransportationType',
                        hoverClass: 'hovered',
                        tolerance: 'pointer',
                        drop: function(event, ui) { handleDropEvent($(this), ui.draggable); }
                    });
                });
                
                setupLayoutClickHandlers();
                $("#layoutDropTTransportationTypeRow").val(totalRows + 1);
            }
        }

        function removeRow(event) {
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            const totalRows = $currentTable.find("tr").length;
            if(totalRows <= 1) {
                showDialog("Cannot remove the last row", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }
            
            $currentTable.find("tr:last").remove();
            $("#layoutDropTTransportationTypeRow").val(totalRows - 1);
        }

        function addColumn(event) {
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            const totalColumns = calculateTotalColumns(); 
            if(totalColumns >= MAX_ROWS_COLS) {
                showDialog(`Maximum ${MAX_ROWS_COLS} columns allowed`, '<?php echo DIALOG_INFORMATION; ?>');
                return;
            }
            
            $currentTable.find("tr").each(function(index) {
                const cellHtml = createColTTransportationType(`_level_${currentLevel}_${index+1}BT${totalColumns + 1}`);
                $(this).append(cellHtml);
                
                $(this).find("td:last").droppable({
                    accept: '.chairTTransportationType, .labelDropTTransportationType',
                    hoverClass: 'hovered',
                    tolerance: 'pointer',
                    drop: function(event, ui) { handleDropEvent($(this), ui.draggable); }
                });
            });
            
            $currentTable.css('width', SEAT_WIDTH * (totalColumns + 1));
            $("#layoutDropTTransportationTypeColumn").val(totalColumns + 1);
            setupLayoutClickHandlers();
        }

        function removeColumn(event) {
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            const totalColumns = calculateTotalColumns(); 
            if(totalColumns <= 1) {
                showDialog("Cannot remove the last column", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }
            
            $currentTable.find("tr").each(function() {
                const $lastCell = $(this).find("td:last");
                if(parseInt($lastCell.attr('colspan')) > 1) {
                    $lastCell.find('b').text('1');
                    unmergeCells();
                } 
                $lastCell.remove();
            });
            
            $currentTable.css('width', SEAT_WIDTH * (totalColumns - 1));
            $("#layoutDropTTransportationTypeColumn").val(totalColumns - 1);
        }

        function createColTTransportationType(id_suffix) {
            return `<td class="layoutDropArea" id="layoutDropTTransportationType${id_suffix}" style="width: ${SEAT_WIDTH}px; height: ${SEAT_HEIGHT}px;"><span class="BoatNumber"></span><span class="BoatLabel"></span><b style="display: none;"></b><div class="layoutDisplaySeat"></div></td>`;
        }
        
        function calculateTotalColumns() {
            let totalColumns = 0;
            $(`#layoutDropTTransportationType_level_${currentLevel}`).find("tr:first td").each(function() {
                totalColumns += parseInt($(this).attr('colspan')) || 1;
            });
            return totalColumns;
        }

        function mergeCells(event) {
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            
            const $selectedCells = $currentTable.find("td.layoutSelectTTransportationType");
            
            if($selectedCells.length < 2) {
                showDialog("Please select at least 2 adjacent cells to merge", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }
            
            // This is a simplified check. It doesn't handle complex selections (e.g. L-shapes).
            const firstCell = $selectedCells.first();
            let isHorizontal = true, isVertical = true;
            const firstRowIndex = firstCell.parent().index(), firstColIndex = firstCell.index();

            $selectedCells.each(function() {
                if ($(this).parent().index() !== firstRowIndex) isHorizontal = false;
                if ($(this).index() !== firstColIndex) isVertical = false;
            });
            
            if (!isHorizontal && !isVertical) {
                showDialog("Please select adjacent cells in a straight line (row or column)", "<?php echo DIALOG_INFORMATION; ?>");
                return;
            }
            
            const totalSpan = $selectedCells.length;
            if(isHorizontal) {
                firstCell.attr('colspan', totalSpan);
                $selectedCells.not(firstCell).remove();
            } else {
                firstCell.attr('rowspan', totalSpan);
                $selectedCells.not(firstCell).remove();
            }
            $currentTable.find("td").removeClass("layoutSelectTTransportationType").find("b").text('');
        }

        function unmergeCells(event) {
            if(event) event.preventDefault();
            const $currentTable = $(`#layoutDropTTransportationType_level_${currentLevel}`);
            
            $currentTable.find("td.layoutSelectTTransportationType").each(function() {
                const $cell = $(this);
                const colspan = parseInt($cell.attr("colspan")) || 1;
                const rowspan = parseInt($cell.attr("rowspan")) || 1;
                
                if(colspan > 1) {
                    for(let c = 1; c < colspan; c++) $cell.after(createColTTransportationType(''));
                    $cell.removeAttr('colspan');
                }
                if(rowspan > 1) {
                    const colIndex = $cell.index();
                    for(let r = 1; r < rowspan; r++) {
                       $cell.parent().nextAll().eq(r - 1).find('td').eq(colIndex).before(createColTTransportationType(''));
                    }
                    $cell.removeAttr('rowspan');
                }
            });
             generateLayoutGrid();
             loadSavedLayout();
        }

        function initializeImageUploads() {
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
                    setupImageDeleteHandlers();
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
            
            // Initialize delete handlers
            setupImageDeleteHandlers();
        }

        function validateImageUpload($form, options) {
            const extArray = [".bmp",".jpg",".gif",".tif",".png"];
            const fileInput = $form.find("input[type=file]")[0];
            
            if (!fileInput.files.length) return false;
            
            const file = fileInput.files[0].name.toLowerCase();
            const isValid = extArray.some(ext => file.endsWith(ext));
            
            if (!isValid) {
                showDialog(`Please only upload files that end in types: <b>${extArray.join("  ")}</b>`, '<?php echo DIALOG_INFORMATION; ?>');
                return false;
            }
            return true;
        }

        function setupImageDeleteHandlers() {
            $(".btnDeleteTransportationTypeOtherImg").off("click").on("click", function() {
                const $imgItem = $(this).closest("li");
                
                showConfirmDialog(
                    "Do you want to delete this image?",
                    "<?php echo DIALOG_CONFIRMATION; ?>",
                    function() {
                        $imgItem.remove();
                    }
                );
            });
        }

        function addTouchSupport() {
            if ('ontouchstart' in window) {
                $.ui.draggable.prototype._mouseStart = function(event) {
                    return $.ui.draggable.prototype._touchStart.apply(this, [event.originalEvent.changedTouches[0]]);
                };
            }
        }

        function showLoadingSpinner() {
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner.gif");
        }

        function hideLoadingSpinner() {
            $(".loader").attr("src", "<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif");
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
echo $this->Form->create('TTransportationType', array('class' => 'form-table-container')); 
echo $this->Form->input('id');
echo $this->Form->hidden('sys_code');
echo $this->Form->hidden('layout'); 
?>
<div class="form-container" id="tTransportationTypeFormContainer">
    <input type="hidden" name="data[TTransportationType][photo_path]" value="<?php echo $link; ?>" />
    <fieldset>
        <legend><?php __(MENU_TRANSPORTATION_TYPE_INFO); ?></legend>
        <table class="form-table">
            <tr class="form-row">
                <td class="form-label-cell" rowspan="3">
                    <table>
                        <tr>
                            <td>
                                <?php
                                $img = "";
                                if(!empty($this->data['TTransportationType']['photo'])){
                                    $img = $this->data['TTransportationType']['photo_path'].$this->webroot."public/transportation_type/".$this->data['TTransportationType']['photo'];
                                }
                                ?>
                                <input type="hidden" name="data[TTransportationType][photo]" id="TTransportationTypePhoto" value="<?php echo $this->data['TTransportationType']['photo']; ?>" />
                                <img src="<?php echo $img; ?>" id="TTransportationTypePhotoDisplay" style="width: 150px; height: 100px;" />
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
                        <?php echo $this->Form->input('amenity_id', array('selected' => $amenitySellected, 'name' => 'data[amenity_id]', 'class' => 'form-select-multi', 'multiple' => true, 'div' => false, 'label' => false)); ?>
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
                            <option value="1" <?php if($this->data['TTransportationType']['seat_type'] == 1){ ?>selected=""<?php } ?>><?php echo "Sitting"; ?></option>
                            <option value="2" <?php if($this->data['TTransportationType']['seat_type'] == 2){ ?>selected=""<?php } ?>><?php echo "Sleeping"; ?></option>
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
                                <td style="width: 18%;"><input type="text" id="layoutDropTTransportationTypeRow" name="data[TTransportationType][total_row]" value="<?php echo $this->data['TTransportationType']['total_row']; ?>" style="width: 90%;" class="integer" /></td>
                                <td style="width: 18%;">Total Column:</td>
                                <td style="width: 18%;"><input type="text" id="layoutDropTTransportationTypeColumn" name="data[TTransportationType][total_column]" value="<?php echo $this->data['TTransportationType']['total_column']; ?>" style="width: 90%;" class="integer" /></td>
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
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="DownStair" /></div>
                            <div class="chairImg" style="background: none; width: 100%; text-align: center;"><span style="display: none;">DownStair</span>Down Stair</div>
                            <div class="chairRemove"><img src="<?php echo $this->webroot; ?>img/button/void.png" class="removeChairTTransportationType" /></div>
                        </div>
                        <div class="labelDropTTransportationType" id="labelDropTTransportationTypeUpStair">
                            <div class="chairLabel"><span class="layoutDropSeat"></span><input type="hidden" value="UpStair" /></div>
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
                    <td valign="top">
                        <ul id="sortablePhoto">
                        <?php
                        $sqlOtherPhoto = mysql_query("SELECT * FROM t_transportation_type_photos WHERE t_transportation_type_id = ".$this->data['TTransportationType']['id']);
                        while($rowOtherPhoto = mysql_fetch_array($sqlOtherPhoto)){
                        ?>
                            <li id="sortPhoto<?php echo $rowOtherPhoto['id']; ?>">
                                <img class="btnDeleteTransportationTypeOtherImg" src="<?php echo $this->webroot; ?>img/button/delete.png" style="z-index: 99999; width: 18px; cursor: pointer;"/>
                                <img src="<?php echo $rowOtherPhoto['photo_path'].$this->webroot; ?>public/transportation_type/<?php echo $rowOtherPhoto['photo']; ?>" style="width: 100px; height: 65px;" />
                                <input type="hidden" value="<?php echo $rowOtherPhoto['photo_path']; ?>" name="data[photo_path_other][]" class="otherImgPathValue" />
                                <input type="hidden" value="<?php echo $rowOtherPhoto['photo']; ?>" name="data[photo_other][]" class="otherImgValue" />
                            </li>
                        <?php
                        }
                        ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </fieldset>
    <br />
    <div style="clear: both;"></div>
</div>
<?php echo $this->Form->end(); ?>