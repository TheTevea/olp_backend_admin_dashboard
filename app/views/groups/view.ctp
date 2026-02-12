<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackGroup").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableGroup.fnDraw(false);
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
            <button type="button" class="form-btn btnBackGroup">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />
<table class="form-table">
    <tr class="form-row">
        <td class="form-label-cell">
            <label class="form-label">
                <?php echo TABLE_NAME; ?> :
            </label>
        </td>
        <td class="form-input-cell">
            <?php echo $group['Group']['name']; ?>
        </td>
    </tr>
</table>