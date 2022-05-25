<div class="container-fluid">
    <div class="row ">
        <div class="col-sm-4 " align="center">
            <h5 class="font_grey">Количество статей на странице: <?=$limit ?></h5>
        </div>
       <!--  <div class="col-sm-2 ">
            <form class="itemsHeader" name="itemsHeader" action="page-1" method="post">
                <select id="itemsOnPageHeader" name="itemsOnPageHeader" onchange="changeItemsQuantityHeader();">
                    <?php echo '<option value="6" '.$selected['4'].' >4</option>
                                <option value="12" '.$selected['8'].' >8</option>
                                <option value="24" '.$selected['12'].' >12</option>' 
                    ?>
                </select>
            </form>
        </div> -->
        <!-- Постраничная навигация -->
        <div class="col-sm-8 " align="right">
            <nav aria-label="Page navigation">
            <?php  if ($total > $limit) { echo $pagination->get(); } ?> 
            </nav>
        </div>
    </div>
</div>

<script>
    function changeItemsQuantityHeader() {
        document.forms['itemsHeader'].submit();
    }
</script>
