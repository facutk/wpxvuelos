<div>
  <fieldset id="stops" class="border-0 pt-3">
    <legend>Escalas</legend>
    <div>
      <input type="radio" id="stops-all" name="stops" value="" checked>
      <label for="stops-all">Todas</label>
    </div>

    <div>
      <input type="radio" id="stops-nonstop" name="stops" value="0">
      <label for="stops-nonstop">Directo</label>
    </div>

    <div>
      <input type="radio" id="stops-one" name="stops" value="1">
      <label for="stops-one">Una o menos</label>
    </div>
  </fieldset>

  <hr class="mt-0 mb-5 ml-3">

  <fieldset id="carriers" class="border-0 pt-3">
    <legend>Aerolíneas</legend>
    <?
      $carriers = $args['carriers'];
      array_unshift( $carriers, ["Name" => 'Todas', "Id" => null]);

      foreach ($carriers as $carrier) {
        $name = $carrier["Name"];
        $id = $carrier["Id"];
    ?>
      <div>
        <input type="checkbox" id="carriers-<? echo $id; ?>" name="carriers" value="<? echo $id; ?>">
        <label for="carriers-<? echo $id; ?>"><? echo $name; ?></label>
      </div>
    <?
      }
    ?>
  </fieldset>

</div>