var availableTags = [
  "ActionScript",
  "AppleScript",
  "Asp",
  "BASIC",
  "C",
  "C++",
  "Clojure",
  "COBOL",
  "ColdFusion",
  "Erlang",
  "Fortran",
  "Groovy",
  "Haskell",
  "Java",
  "JavaScript",
  "Lisp",
  "Perl",
  "PHP",
  "Python",
  "Ruby",
  "Scala",
  "Scheme"
];

(function( $ ) {
  "use strict";
  $(document).ready( function() {


    // $( "#tags" ).autocomplete({
    //   source: availableTags
    // });

    $( "#tags" ).autocomplete({
      source: "/wp-json/xvuelos/v1/places",
      minLength: 2,
      select: function( event, ui ) {
        console.log(ui);
        // log( "Selected: " + ui.item.value + " aka " + ui.item.id );
      }
    });


  });
})(jQuery);
