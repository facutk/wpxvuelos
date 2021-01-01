<?
  $markets = xvuelos_get_markets();
  $currencies = xvuelos_get_currencies();

  global $userinfo;
?>

<form action="" method="POST">
  <div class="row">
    <div class="col">
      <select
        name="market"
        class="border-grey border-0 text-muted"
        onchange="this.form.submit()"
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
        name="currency"
        class="border-grey border-0 text-muted"
        onchange="this.form.submit()"
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
</form>