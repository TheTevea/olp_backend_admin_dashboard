<script type="text/javascript">
    $(document).ready(function(){
        // Prevent Key Enter
        preventKeyEnter();
        $("#agencyApiFilterAgency").chosen({width: 160});
        $("#btnRefreshAgencyApi").unbind('click').click(function(){
            searchAgencyAPiBooked();
        });
        searchAgencyAPiBooked();
    });

    function searchAgencyAPiBooked(){
        var url = "agencyApiBookedResult";
        if($("#agencyApiFilterViewBy").val() == "2"){
            url = "agencyApiBookedDetail";
        }   
        $.ajax({
            type: "POST",
            url: "<?php echo $this->base . '/'; ?>t_tickets/"+url+"/"+$("#agencyApiFilterAgency").val(),
            beforeSend: function(){
                $(".loader").attr('src','<?php echo $this->webroot; ?>img/layout/spinner.gif');
                $("#agencyApiRecordResult").html("<?php echo ACTION_LOADING; ?>");
                $("#lblRefreshAgencyApi").html("<?php echo ACTION_LOADING; ?>");
                $("#btnRefreshAgencyApi").attr("disabled", true);
            },
            success: function(result){
                $(".loader").attr('src', '<?php echo $this->webroot; ?>img/layout/spinner-placeholder.gif');
                $("#lblRefreshAgencyApi").html("<?php echo GENERAL_SEARCH; ?>");
                $("#btnRefreshAgencyApi").attr("disabled", false);
                $("#agencyApiRecordResult").html(result);
            }
        });
    }
</script>
<div class="leftPanel">
    <!-- Header Filter -->
    <div class="filter-container">
        <div class="filter-row">
            <!-- View By Filter -->
            <div class="filter-group">
                <select id="agencyApiFilterViewBy" class="filter-select">
                    <option value="1" selected=""><?php echo "Summary"; ?></option>
                    <option value="2"><?php echo "Detail"; ?></option>
                </select>
            </div>
            <!-- Agency Filter -->
            <div class="filter-group">
                <select id="agencyApiFilterAgency" class="filter-select">
                    <option value="all"><?php echo TABLE_ALL; ?></option>
                    <?php
                    foreach($agencyApis AS $agencyApi){
                    ?>
                    <option value="<?php echo $agencyApi['TAgent']['id']; ?>"><?php echo $agencyApi['TAgent']['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <!-- Search Button -->
            <div class="filter-group">
                <button type="button" class="positive-btn" id="btnRefreshAgencyApi">
                    <i class="fas fa-search"></i>
                    <span id="lblRefreshSearchTicket"><?php echo GENERAL_SEARCH; ?></span>
                </button>
            </div>
        </div>
    </div>
    <br />
    <div id="agencyApiRecordResult"></div>
    <br />
    <br />
</div>
<div class="rightPanel"></div>