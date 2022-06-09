<div class="container-fluid">
    <div class="row ">
        <div class="col-sm-4 " align="center">
            <h5 class="font_grey">Количество статей на странице: <?= $limit ?></h5>
        </div>
        <!-- Постраничная навигация -->
        <div class="col-sm-8 " align="right">
            <nav aria-label="Page navigation">
                <?php if ($total > $limit) {
                    echo $pagination->get();
                } ?>
            </nav>
        </div>
    </div>
</div>

<script>
    function changeItemsQuantityHeader() {
        document.forms['itemsHeader'].submit();
    }
</script>