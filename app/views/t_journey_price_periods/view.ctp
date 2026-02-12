<?php
include("includes/function.php");
?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".btnBackTJourneyPricePeriod").unbind('click').click(function(event){
            event.preventDefault();
            oCache.iCacheLower = -1;
            oTableTJourneyPricePeriod.fnDraw(false);
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
            <button type="button" class="form-btn btnBackTJourneyPricePeriod">
                <i class="fas fa-chevron-left"></i>
                <span><?php echo ACTION_BACK; ?></span>
            </button>
        </div>
    </div>
</div>
<br />

<!-- Read-Only Content (Format#3) -->
<fieldset>
    <legend><?php __(MENU_SET_PRICE_PERIOD_INFO); ?></legend>
    <table class="form-table">
        <!-- Apply To -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_APPLY_TO); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php 
                if($this->data['TJourneyPricePeriod']['apply_to'] == 1){
                    echo TABLE_ALL;
                } else {
                    echo MENU_MAIN_BRANCH;
                }
                ?>
            </td>
        </tr>

        <!-- Conditional Main Branch -->
        <?php if($this->data['TJourneyPricePeriod']['apply_to'] == 2): ?>
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(MENU_MAIN_BRANCH); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['MainBranch']['name']; ?>
            </td>
        </tr>
        <?php endif; ?>

        <!-- Description -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(GENERAL_DESCRIPTION); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TJourneyPricePeriod']['name']; ?>
            </td>
        </tr>

        <!-- Destinations -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_DESTINATION_FROM); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $destFrom; ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_DESTINATION_TO); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $destTo; ?>
            </td>
        </tr>

        <!-- Transportation Type -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(MENU_TRANSPORTATION_TYPE); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['TTransportationType']['name']; ?>
            </td>
        </tr>

        <!-- Dates -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_START_DATE); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo dateShort($this->data['TJourneyPricePeriod']['start']); ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_END_DATE); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo dateShort($this->data['TJourneyPricePeriod']['end']); ?>
            </td>
        </tr>

        <!-- Price Type -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_PRICE_TYPE); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php 
                if($this->data['TTransportationType']['price_type'] == 1){
                    echo TABLE_FIX_AMOUNT;
                } else if($this->data['TTransportationType']['price_type'] == 2){
                    echo TABLE_MARKUP_AMOUNT;
                }
                ?>
            </td>
        </tr>

        <!-- Prices -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __("Selling Price ".TABLE_NORMAL); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo number_format($this->data['TJourneyPricePeriod']['price'], 2); ?> <?php echo $symbol; ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __("Selling Price ".TABLE_FOREIGNER); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo number_format($this->data['TJourneyPricePeriod']['foreigner_price'], 2); ?> <?php echo $symbol; ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __("Selling Price VIP Card"); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo number_format($this->data['TJourneyPricePeriod']['membership'], 2); ?> <?php echo $symbol; ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __("Agency Price (Khmer)"); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo number_format($this->data['TJourneyPricePeriod']['agency_price'], 2); ?> <?php echo $symbol; ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __("Agency Price (Foreigner)"); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo number_format($this->data['TJourneyPricePeriod']['agency_price_foreigner'], 2); ?> <?php echo $symbol; ?>
            </td>
        </tr>

        <!-- Created Info -->
        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_CREATED); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo dateShort($this->data['TJourneyPricePeriod']['created'], "d/m/Y H:i:s"); ?>
            </td>
        </tr>

        <tr class="form-row">
            <td class="form-label-cell">
                <label class="form-label"><?php __(TABLE_CREATED_BY); ?> :</label>
            </td>
            <td class="form-input-cell">
                <?php echo $this->data['User']['username']; ?>
            </td>
        </tr>
    </table>
</fieldset>