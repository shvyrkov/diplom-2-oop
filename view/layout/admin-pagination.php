<div class="container-fluid">
    <div class="row ">
        <div class="col-sm-4 " align="right">
            <h5 class="font_grey">Количество на странице: </h5>
        </div>
       <div class="col-sm-1 " align="right">
            <form class="itemsHeader" name="itemsHeader" action="" method="get">
                <select id="itemsOnPageHeader" name="itemsOnPageHeader" onchange="changeItemsQuantityHeader();">
                    <option value="10" <?=$selected['10'] ?> >10</option>
                    <option value="20" <?=$selected['20'] ?> >20</option>
                    <option value="50" <?=$selected['50'] ?> >50</option> 
                    <option value="200" <?=$selected['200'] ?> >200</option> 
                    <option value="all" <?=$selected['all'] ?> >Все</option><!-- @TODO: кол-во брать из Articles::all()->count() -->
                    <!-- <option value="<=Articles::all()->count() ?>" <=$selected['all'] ?> >Все</option> -->
                </select>
            </form>
        </div>
        <div class="col-sm-2 " align="right">
            <h5 class="font_grey">Всего: <?=$total ?></h5>
        </div>
        <!-- Постраничная навигация -->
        <div class="col-sm-5 " align="right">
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
