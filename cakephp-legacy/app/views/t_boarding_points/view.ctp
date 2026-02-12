<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $(".btnBackTBoardingPoint").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTBoardingPoint.fnDraw(false);
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
            <button type="button" class="form-btn btnBackTBoardingPoint">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />

<!-- Read-Only Content (Format#3) -->
<fieldset>
    <legend><?php __(MENU_VIEW_BOARDING_POINT); ?></legend>
    <table class="form-table">
        <!-- Name Fields -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_NAME); ?> (Khmer) :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TBoardingPoint']['name_kh']; ?>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_NAME); ?> (English) :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TBoardingPoint']['name']; ?>
            </td>
        </tr>
        
        <!-- Contact Information -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_CONTACT_NAME); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TBoardingPoint']['contact']; ?>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_TELEPHONE); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TBoardingPoint']['telephone']; ?>
            </td>
        </tr>
        
        <!-- Address Fields -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_ADDRESS); ?> (Khmer) :</label>
            </td>
            <td class="form-input-cell">
                <?php echo nl2br($this->data['TBoardingPoint']['address_kh']); ?>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_ADDRESS); ?> (English) :</label>
            </td>
            <td class="form-input-cell">
                <?php echo nl2br($this->data['TBoardingPoint']['address']); ?>
            </td>
        </tr>
        
        <!-- Coordinates -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_LONG); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TBoardingPoint']['longs']; ?>
            </td>
        </tr>
        
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_LAT); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TBoardingPoint']['lats']; ?>
            </td>
        </tr>
    </table>
</fieldset>