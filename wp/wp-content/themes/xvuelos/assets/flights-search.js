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
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append("<div>" + item.label + " - " + "<small>" + item.trait + "</small>" + "</div>")
        .appendTo( ul );
    };
  });
})(jQuery);


// b = document.createElement("DIV");
//         /*make the matching letters bold:*/
//         if (label.substr(0, query.length).toUpperCase() == query.toUpperCase()) {
//           b.innerHTML = "<strong>" + label.substr(0, query.length) + "</strong>";
//           b.innerHTML += label.substr(query.length);
//         } else {
//           b.innerHTML = label;
//         }
//         b.innerHTML += ' - ' + '<small>' + trait + '</small>';
//         /*insert a input field that will hold the current array item's value:*/
//         b.innerHTML += "<input type='hidden' value='" + value + "'>";
//         /*execute a function when someone clicks on the item value (DIV element):*/
//             b.addEventListener("click", function(e) {
//             /*insert the value for the autocomplete text field:*/
//             inp.value = this.getElementsByTagName("input")[0].value;
//             /*close the list of autocompleted values,
//             (or any other open lists of autocompleted values:*/
//             closeAllLists();
//         });
//         a.appendChild(b);