<?
  $selectedSortby = get_query_var("sortby");
  $sortByOptions = [
    [
      "label" => "Mejor opción",
      "value" => null
    ],
    [
      "label" => "Más rápido",
      "value" => "duration"
    ],
    [
      "label" => "Mejor precio",
      "value" => "price"
    ],
  ];
?>

<div class="d-flex mb-3" role="group" aria-label="Basic example">
  <span class="text-nowrap mr-2 py-3">Ordenar por</span>
  <select
    name="sortby"
    class="w-100 p-3 border-grey bg-white"
  >
    <?
      foreach($sortByOptions as $option) {
        $label = $option["label"];
        $value = $option["value"];
        $selected = $selectedSortby == $value;
    ?>
      <option
        value="<? echo $value; ?>"
        <? if ($selected) echo 'selected'; ?>
      >
        <? echo $label; ?>
      </option>
    <?
      }
    ?>
  </select>
</div>