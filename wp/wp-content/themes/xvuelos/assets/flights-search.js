(function( $ ) {
  "use strict";
  $(document).ready( function() {

    $.ui.autocomplete.prototype._renderItem = function( ul, item ) {
      return $("<li></li>")
        .append(
          "<div>"
          + String(item.label)
            .replace(
              new RegExp(this.term, "gi"),
              "<strong>$&</strong>"
            )
          + " - "
          + "<small>" + item.trait + "</small>"
          + "</div>")
        .appendTo( ul );
    };

    $( "#flights-search-origin" )
      .autocomplete({
        source: "/wp-json/xvuelos/v1/places?station=origin",
        minLength: 0
      })    
      .focus(function () {
        $(this).autocomplete("search", $( "#flights-search-origin" ).val());
      });

    $( "#flights-search-destination" )
      .autocomplete({
        source: "/wp-json/xvuelos/v1/places?station=destination",
        minLength: 2
      }); // TODO: find out if there is any way to retrieve popular destinations


    function handleSearch() {
      var origin = jQuery("#flights-search-origin").val();
      var destination = jQuery("#flights-search-destination").val();
      var departureDate = jQuery("#flights-search-departure-date").val();
      var returnDate = jQuery("#flights-search-return-date").val();
      var sortby = jQuery("select[name='sortby']").val();
      var stops = jQuery("input[name='stops']:checked").val();
      var offset = jQuery("input[name='offset']").val();
      if (offset === "0") {
        offset = "";
      }

      var carriers = [];
      jQuery("input[name='carriers']:checked").each(function () {
        var value = jQuery(this).val();
        carriers.push(parseInt(value));
      });

      carriers = carriers.join(';')

      var url = "/vuelos/"
        + origin + "/"
        + destination + "/"
        + departureDate + "/"
        + returnDate + "/";

      var queryParams = { offset, sortby, stops, carriers };
      var queryString = Object.keys(queryParams).reduce(function (acc, key){
        if (queryParams[key]) {
          if (acc) {
            acc += '&';
          }
          acc += key + '=' + queryParams[key];
        }
        return acc;
      }, "");

      if (queryString) {
        url += '?' + queryString;
      }

      console.log(url);
      window.location.href = url; //'/vuelos/EZE/MIA/2020-10-13/2020-10-28';
    };

    function handleMoreResults() {
      var offset = parseInt(jQuery("input[name='offset']").val());
      jQuery("input[name='offset']").val(offset + 1);
      handleSearch();
    }
    function handleFilterChange() {
      jQuery("input[name='offset']").val(0);
      handleSearch();
    }

    $("#flights-search-form").on('submit', function(e){
      e.preventDefault();
      handleSearch();
   });
   $("select[name='sortby']").on('change', handleSearch);
   $("input[name='stops']").on('change', handleFilterChange);
   $("input[name='carriers']").on('change', handleFilterChange);

   $("input[name='more-results']").on('click', handleMoreResults);
   
  });
})(jQuery);
