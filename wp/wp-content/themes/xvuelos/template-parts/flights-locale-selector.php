<?
  $markets = [
    'US', 'AR', 'BR', 'CL', 'CO', 'EC', 'ES', 'MX', 'PE', 'PA', 'UY'
  ];

  $currencies = [
    'USD', 'ARS', 'BRL', 'CLP', 'COP', 'ECS', 'EUR', 'MXN', 'PEN', 'PYG', 'UYU'
  ];
  global $userinfo;
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