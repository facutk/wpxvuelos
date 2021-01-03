<?
  $offset = $args['offset'];
  $total = $args['total'];
  $pagesize = $args['pagesize'];

  $noMoreResults = (($offset + 1) * $pagesize) >= $total;
?>

<input
  name="more-results"
  type="submit"
  class="search-submit w-100"
  value="Mas Resultados"
  <? if ($noMoreResults) echo 'disabled'; ?>
/>
<input
  type="hidden"
  name="offset"
  value="<? echo $offset; ?>"
/>