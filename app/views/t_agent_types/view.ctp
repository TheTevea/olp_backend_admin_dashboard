<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackTAgentType").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTAgentType.fnDraw(false);
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
            <button type="button" class="form-btn btnBackTAgentType">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />

<!-- Read-Only Content (Format#3) -->
<fieldset>
    <legend><?php __(MENU_AGENT_TYPE_INFO); ?></legend>
    <table class="form-table">
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_NAME); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TAgentType']['name']; ?>
            </td>
        </tr>
    </table>
</fieldset>