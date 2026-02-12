<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackUser").unbind("click").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableUser.fnDraw(false);
            var rightPanel = $(this).parent().parent().parent().parent();
            var leftPanel  = rightPanel.parent().find(".leftPanel");
            rightPanel.hide();rightPanel.html("");
            leftPanel.show("slide", { direction: "left" }, 500);
        });
    });
</script>
<div class="filter-container">
    <div class="filter-row">
        <div class="filter-form-group">
            <button type="button" class="form-btn btnBackUser">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />
<fieldset>
    <legend><?php __(MENU_USER_MANAGEMENT_INFO); ?></legend>
    <table class="form-table">
        <tr class="form-row">
            <td class="form-label-cell">
                <label for="UserFirstName" class="form-label">
                    <?php echo TABLE_FIRST_NAME; ?> :
                </label>
            </td>
            <td class="form-input-cell">
                <div class="form-input-container">
                    <?php echo $user['User']['first_name']; ?>
                </div>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label for="UserLastName" class="form-label">
                    <?php echo TABLE_LAST_NAME; ?> :
                </label>
            </td>
            <td class="form-input-cell">
                <div class="form-input-container">
                    <?php echo $user['User']['last_name']; ?>
                </div>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label for="UserTelephone" class="form-label">
                    <?php echo TABLE_TELEPHONE; ?> :
                </label>
            </td>
            <td class="form-input-cell">
                <div class="form-input-container">
                    <?php echo $user['User']['telephone']; ?>
                </div>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label for="UserEmail" class="form-label">
                    <?php echo TABLE_EMAIL; ?> :
                </label>
            </td>
            <td class="form-input-cell">
                <div class="form-input-container">
                    <?php echo $user['User']['email']; ?>
                </div>
            </td>
        </tr>
    </table>
</fieldset>