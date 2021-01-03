<?
  $offset = get_query_var("offset");
  if (!$offset) {
    $offset = 0;
  }

  function genPageUrl($pageNumber) {
    global $origin;
    global $destination;
    global $departure;
    global $arrival;

    return "?"
      . "results=true"
      . "&" . "page=" . $pageNumber
      . "&" . "origin=" . $origin
      . "&" . "destination=" . $destination
      . "&" . "departure=" . $departure
      . "&" . "arrival=" . $arrival;
  };

  // $noMoreResults = (($page + 1) * $PAGE_SIZE) >= $total;
?>

<!-- <nav aria-label="...">
  <ul class="pagination my-3">
    <li class="page-item <? echo $page === 0 ? "disabled" : '' ?>">
      <a
        class="page-link"
        href="<? echo genPageUrl(0) ?>"
        tabindex="-1"
        aria-disabled="true"
      >
        Inicio
      </a>
    </li>

    <li class="page-item <? echo $noMoreResults === true ? "disabled" : '' ?>">
      <a
        class="page-link"
        href="<? echo genPageUrl($page + 1) ?>"
      >
        Mas Resultados
      </a>
    </li>
  </ul>
</nav> -->

<input name="more-results" type="submit" class="search-submit w-100" value="Mas Resultados" />
<input type="hidden" name="offset" value="<? echo $offset; ?>" />