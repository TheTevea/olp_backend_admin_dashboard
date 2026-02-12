<!-- QR Code -->
<script type="text/javascript" src="<?php echo $this->webroot; ?>js/jquery.qrcode.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".btnBackAmenity").click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableAmenity.fnDraw(false);
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
            <button type="button" class="form-btn btnBackAmenity">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />
<fieldset>
    <legend><?php __(MENU_AMENITY_INFO); ?></legend>
    <table class="form-table">
        <tr class="form-row">
            <td colspan="2">
                <?php
                $img = "";
                if(!empty($this->data['Amenity']['photo'])){
                    $img = $this->webroot."public/amenities/".$this->data['Amenity']['photo'];
                }
                ?>
                <img src="<?php echo $img; ?>" />
            </td>
        </tr>
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php echo TABLE_NAME; ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['Amenity']['name']; ?>
            </td>
        </tr>
    </table>
</fieldset>