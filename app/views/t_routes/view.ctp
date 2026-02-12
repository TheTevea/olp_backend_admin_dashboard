<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackTRoute").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTRoute.fnDraw(false);
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
            <button type="button" class="form-btn btnBackTRoute">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />
<fieldset>
    <legend><?php __(MENU_ROUTE_INFO); ?></legend>
    <table class="form-table">
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php echo TABLE_NAME; ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TRoute']['name']; ?>
            </td>
        </tr>
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php echo GENERAL_DESCRIPTION; ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo nl2br($this->data['TRoute']['description']); ?>
            </td>
        </tr>
    </table>
</fieldset>