<?
  $countries = [
    'AR', 'BR', 'CL', 'CO', 'EC', 'ES', 'MX', 'PE', 'PA', 'US', 'UY'
  ];

  $currencies = [
    'ARS', 'BRL', 'CLP', 'COP', 'ECS', 'EUR', 'MXN', 'PEN', 'PYG', 'USD', 'UYU'
  ];
?>

<div class="row">
  <div class="col">
    <select
      id="countrycode"
      name="countrycode"
      class="border-grey border-0 text-muted"
      form="flights-search-form'"
      onchange="handleSubmit(event)"
    >
      <?
        foreach($countries as $country) {
      ?>
        <option value="<? echo $country; ?>">
          <? echo $country; ?>
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
        <option value="<? echo $currency; ?>">
          <? echo $currency; ?>
        </option>
      <? } ?>
    </select>
  </div>
</div>