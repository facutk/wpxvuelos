<?
  $selectedStops = get_query_var("stops");

  $stopsOptions = [
    [
      "label" => "Todas",
      "value" => null
    ],
    [
      "label" => "Directo",
      "value" => "0"
    ],
    [
      "label" => "Una o menos",
      "value" => "1"
    ],
  ];
?>

<div>
  <fieldset id="stops" class="border-0 pt-3">
    <legend>Escalas</legend>
    <?
      foreach($stopsOptions as $option) {
        $label = $option["label"];
        $value = $option["value"];
        $checked = $selectedStops == $value;
    ?>
      <div>
        <input
          type="radio"
          id="stops-<? echo $value; ?>"
          name="stops"
          value="<? echo $value; ?>"
          <? if ($checked) echo 'checked'; ?>
        />
        <label for="stops-<? echo $value; ?>" >
          <? echo $label; ?>
        </label>
      </div>
    <?
      }
    ?>
  </fieldset>

  <hr class="mt-0 mb-5 ml-3">

  <fieldset id="carriers" class="border-0 pt-3">
    <legend>Aerolíneas</legend>
    <?
      $carriers = $args['carriers'];
      $selectedCarriers = [];
      if (get_query_var("carriers")) {
        $selectedCarriers = explode(";", get_query_var("carriers"));
      }

      foreach ($carriers as $carrier) {
        $name = $carrier["Name"];
        $code = $carrier["Code"];
        $checked = in_array($code, $selectedCarriers);
    ?>
      <div>
        <input
          type="checkbox"
          id="carriers-<? echo $code; ?>"
          name="carriers"
          value="<? echo $code; ?>"
          <? if ($checked) echo 'checked'; ?>
        />
        <label for="carriers-<? echo $code; ?>">
          <? echo $name; ?>
        </label>
      </div>
    <?
      }
    ?>
  </fieldset>

</div>