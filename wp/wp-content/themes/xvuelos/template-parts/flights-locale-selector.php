<?
  $markets = xvuelos_get_markets();
  $currencies = xvuelos_get_currencies();

  global $userinfo;

  // var_dump($userinfo);
?>

<div class="row">
  <div class="col">
    <select
      name="marketcode"
      class="border-grey border-0 text-muted"
      form="flights-search-form'"
      onchange="handleSubmit(event)"
    >
      <?
        foreach($markets as $market) {
      ?>
        <option
          value="<? echo $market; ?>"
          <? echo ($market === $userinfo['market']) ? "selected" : ''; ?>
        >
          <? echo $market; ?>
        </option>
      <? } ?>
    </select>
  </div>
  <div class="col">
    <select
      name="currencycode"
      class="border-grey border-0 text-muted"
      form="flights-search-form'"
      onchange="handleSubmit(event)"
    >
      <?
        foreach($currencies as $currency) {
      ?>
        <option
          value="<? echo $currency; ?>"
          <? echo ($currency === $userinfo['currency']) ? "selected" : ''; ?>
        >
          <? echo $currency; ?>
        </option>
      <? } ?>
    </select>
  </div>
</div>