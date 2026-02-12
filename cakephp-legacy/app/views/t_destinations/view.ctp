<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackTDestination").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTDestination.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });
    });
</script>
<!-- Button Header (Format#3) -->
<div class="filter-container">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackTDestination">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />

<!-- Read-Only Content (Format#3) -->
<fieldset>
    <legend><?php __(MENU_DESTINATION_INFO); ?></legend>
    <div class="form-column-container">
        <!-- Left Column -->
        <div class="form-column">
            <table class="form-table">
                <!-- <tr class="form-row">
                    <td class="form-label-cell">
                        <label class="form-label"><?php //__(MENU_PROVINCE_MANAGEMENT); ?> :</label>
                    </td>
                    <td class="form-input-cell">
                        <?php //echo $this->data['Province']['name']; ?>
                    </td>
                </tr> -->
                <tr class="form-row">
                    <td class="form-label-cell">
                        <label class="form-label"><?php __(TABLE_CODE); ?> :</label>
                    </td>
                    <td class="form-input-cell">
                        <?php echo $this->data['TDestination']['code']; ?>
                    </td>
                </tr>
                <tr class="form-row">
                    <td class="form-label-cell">
                        <label class="form-label"><?php __(TABLE_NAME); ?> (Khmer) :</label>
                    </td>
                    <td class="form-input-cell">
                        <?php echo $this->data['TDestination']['name_kh']; ?>
                    </td>
                </tr>
                <tr class="form-row">
                    <td class="form-label-cell">
                        <label class="form-label"><?php __(TABLE_NAME); ?> (English) :</label>
                    </td>
                    <td class="form-input-cell">
                        <?php echo $this->data['TDestination']['name']; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Right Column -->
        <div class="form-column">
            <table class="form-table">
                <tr class="form-row">
                    <td class="form-label-cell">
                        <label class="form-label"><?php __(REPORT_TO); ?> :</label>
                    </td>
                    <td class="form-input-cell">
                        <?php
                            $destiTo = '';
                            $sqlTo = mysql_query("SELECT GROUP_CONCAT(name) FROM t_destinations WHERE id IN (SELECT t_destination_to_id FROM t_destination_tos WHERE t_destination_from_id = ".$this->data['TDestination']['id']." AND is_active = 1)");
                            if(mysql_num_rows($sqlTo)){
                                $rowTo = mysql_fetch_array($sqlTo);
                                $destiTo = $rowTo[0];
                            }
                            echo $destiTo;
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>