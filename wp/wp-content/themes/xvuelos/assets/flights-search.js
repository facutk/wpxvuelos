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
        $(this).autocomplete("search", "");
      });

    $( "#flights-search-destination" )
      .autocomplete({
        source: "/wp-json/xvuelos/v1/places?station=destination",
        minLength: 2
      }); // TODO: find out if there is any way to retrieve popular destinations

  });
})(jQuery);
