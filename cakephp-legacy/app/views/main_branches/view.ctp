<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackMainBranch").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableMainBranch.fnDraw(false);
            var rightPanel=$(this).parent().parent().parent().parent();
            var leftPanel=rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });
    });
</script>
<div class="filter-container">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackMainBranch">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />
<fieldset>
    <legend><?php __(MENU_MAIN_BRANCH_INFO); ?></legend>
    <table class="form-table">
        <tr class="form-row">
            <td class="form-label-cell"><label class="form-label"><?php echo TABLE_NAME; ?> :</label></th>
            <td class="form-input-cell"><?php echo $this->data['MainBranch']['name']; ?></td>
        </tr>
        <tr class="form-row">
            <td class="form-label-cell"><label class="form-label"><?php echo TABLE_ORIGIN; ?> :</label></th>
            <td class="form-input-cell"><?php echo $this->data['TDestination']['name']; ?></td>
        </tr>
    </table>
</fieldset>