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

      var carriers = [];
      var isAllCarriersChecked;
      jQuery("input[name='carriers']:checked").each(function () {
        var value = jQuery(this).val();
        if (!value) {
          isAllCarriersChecked = true;
        }
        carriers.push(parseInt(value));
      });
      if (isAllCarriersChecked) {
        carriers = [];
      }

      carriers = carriers.join(';')

      console.log({
        origin, destination, departureDate, returnDate, sortby, stops, carriers
      });

      var url = "/vuelos/"
        + origin + "/"
        + destination + "/"
        + departureDate + "/"
        + returnDate + "/";
      
      var appendSymbol = '?';
      
      if (sortby) {
        url = url
          + appendSymbol
          + 'sortby=' + sortby;
        appendSymbol = '&';
      }

      if (stops) {
        url = url
          + appendSymbol
          + 'stops=' + stops;
        appendSymbol = '&';
      }

      if (carriers) {
        url = url
          + appendSymbol
          + 'carriers=' + carriers;
        appendSymbol = '&';
      }

      window.location.href = url; //'/vuelos/EZE/MIA/2020-10-13/2020-10-28';
    };

    $("#flights-search-form").on('submit', function(e){
      e.preventDefault();
      handleSearch();
   });
   $("select[name='sortby']").on('change', handleSearch);
   $("input[name='stops']").on('change', handleSearch);
   $("input[name='carriers']").on('change', handleSearch);
  });
})(jQuery);
