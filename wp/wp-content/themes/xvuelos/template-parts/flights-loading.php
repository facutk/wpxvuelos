<?php
/**
 * Displays the Flights Results Loading Skeleton
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
?>

<div class="row">
  <div class="col-md-3 mb-4">
    <details id="filter-details">
      <summary></summary>
      <!-- filters -->
      <div class="ssc mb-5">
        <div class="ssc-wrapper">
          <div class="ssc-head-line mb"></div>
          <div class="ssc-line mb w-40"></div>
          <div class="ssc-line mb w-60"></div>
          <div class="ssc-line mb w-80"></div>
          <div class="ssc-line mb w-70"></div>
        </div>
      </div>

      <hr class="mt-0 mb-5 ml-3">

      <div class="ssc">
        <div class="ssc-wrapper">
          <div class="ssc-head-line mb"></div>
          <div class="ssc-line mb w-80"></div>
          <div class="ssc-line mb w-40"></div>
          <div class="ssc-line mb w-30"></div>
          <div class="ssc-line mb w-60"></div>
          <div class="ssc-line mb w-80"></div>
          <div class="ssc-line mb w-70"></div>
          <div class="ssc-line mb w-40"></div>
          <div class="ssc-line mb w-30"></div>
        </div>
      </div>
    </details>
  </div>

  <!-- Itineraries -->
  <div class="col-md-7">
    <!-- sortby -->
    <div class="ssc-square mb" style="height: 50px"></div>

    <? foreach(range(1, 3) as $n) { ?>

      <div class="card my-3">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9">
              <? foreach (range(1, 2) as $leg) { ?>
                  <h6 class="card-subtitle mb-5 text-muted">
                    <div class="ssc-head-line mb w-80"></div>
                  </h6>

                  <div class="card-text mb-5">
                    <div class="row">
                      <div class="col-md-3 mb-5">
                        <div class="ssc">
                          <div class="ssc-square mb" style="width: 24px; height: 24px"></div>
                          <div class="ssc-line mb w-50"></div>
                        </div>
                      </div>
                      
                      <div class="col-md-9">
                        <div class="row">
                          <div class="col">
                            <div class="ssc">
                              <div class="ssc-line w-50 mb" ></div>
                              <div class="ssc-line w-80 mb" ></div>
                              <div class="ssc-line w-50 mb" ></div>
                            </div>
                          </div>
                          <div class="col">
                            <div class="ssc">
                              <div class="ssc-line w-40 mb" ></div>
                              <div class="ssc-line w-50 mb" ></div>
                            </div>
                          </div>
                          <div class="col">
                            <div class="ssc">
                              <div class="ssc-line w-40 mb" ></div>
                              <div class="ssc-line w-80 mb" ></div>
                              <div class="ssc-line w-60 mb" ></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                <?
                }
              ?>
            </div>
            <div class="col-lg-3">
              <div class="row">
                <div class="col">
                  <div class="ssc-square w-100" style="height: 40px"></div>
                </div>
                <div class="col py-2">
                  <div class="ssc" style="min-width: 120px">
                    <div class="ssc-head-line mb" ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>

    <?
      }
    ?>
    <!-- pagination -->
    <div class="ssc-square w-100" style="height: 50px"></div>
  </div>

  <div class="col-md-2 mb-4 d-none d-md-block">
    <!-- ads 160x600 -->  
    <div class="ssc-square" style="width: 160px; height: 600px"></div>
  </div>
</div>

<script>
  const mediaQuery = window.matchMedia('(min-width: 768px)')

  function handleTabletChange(e) {
    if (e.matches) {
      document.getElementById("filter-details").setAttribute("open", true);
    }
  }

  mediaQuery.addListener(handleTabletChange)

  handleTabletChange(mediaQuery);
</script>
