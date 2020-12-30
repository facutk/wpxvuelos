<?php
/**
 * Displays the Flights Search Form
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

  $origin = get_query_var('origin');
  $destination = get_query_var('destination');
  $outboundDate = get_query_var('outboundDate');
  $inboundDate = get_query_var('inboundDate');

  $today = date("Y-m-d");
  if (strlen($outboundDate) == 0) {
    $outboundDate = $today;
  }
  if (strlen($inboundDate) == 0) {
    $inboundDate = $today;
  }

  // $formComplete = strlen($origin) > 0 && strlen($destination) > 0 && strlen($outboundDate) > 0 && strlen($inboundDate) > 0;
?>

<div class="my-5">
  <form autocomplete="off" role="search" class="search-form" id="flights-search-form">
    <label for="flights-search-origin" class="autocomplete">
      <span class="screen-reader-text">Origen</span>
      <input type="search" id="flights-search-origin" class="search-field" placeholder="Origen" value="<?php echo $origin ?>" name="origin">
    </label>

    <label for="flights-search-destination" class="autocomplete">
      <span class="screen-reader-text">Destino</span>
      <input type="search" id="flights-search-destination" class="search-field" placeholder="Destino" value="<?php echo $destination ?>" name="destination">
    </label>

    <label for="flights-search-departure-date">
      <span class="screen-reader-text">Salida</span>
      <input type="date" id="flights-search-departure-date" class="search-field" placeholder="Salida" value="<?php echo $outboundDate ?>" name="outboundDate" min="<?php echo $today ?>">
    </label>

    <label for="flights-search-return-date">
      <span class="screen-reader-text">Regreso</span>
      <input type="date" id="flights-search-return-date" class="search-field" placeholder="Regreso" value="<?php echo $inboundDate ?>" name="inboundDate" min="<?php echo $today ?>">
    </label>

    <input type="submit" class="search-submit" value="Buscar">
  </form>

  <input id="tags" hidden>
</div>

<style>

.search-form {
  flex-direction: column;
}
@media (min-width: 1024px) {
  .search-form {
    flex-direction: row;
  } 
}

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
  margin: -0.8rem 0 0.8rem 0.8rem;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9;
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important;
  color: #ffffff;
}
</style>

<script>

function debounce(func, wait) {
  let timeout;

  // This is the function that is returned and will be executed many times
  // We spread (...args) to capture any number of parameters we want to pass
  return function executedFunction(...args) {

    // The callback function to be executed after 
    // the debounce time has elapsed
    const later = () => {
      // null timeout to indicate the debounce ended
      timeout = null;
      
      // Execute the callback
      func(...args);
    };
    // This will reset the waiting every function execution.
    // This is the step that prevents the function from
    // being executed because it will never reach the 
    // inside of the previous setTimeout  
    clearTimeout(timeout);
    
    // Restart the debounce waiting period.
    // setTimeout returns a truthy value (it differs in web vs Node)
    timeout = setTimeout(later, wait);
  };
};

function autocomplete(inp, direction) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/

  var debouncedSearch = debounce(function(e) {
    var a, b, i;
    var query = e.target.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!query) { return false; }

    // loading
    var oReq = new XMLHttpRequest();
    oReq.addEventListener("load", function(r) {
      var places = JSON.parse(r.target.response);

      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", e.target.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      
      /*append the DIV element as a child of the autocomplete container:*/
      e.target.parentNode.appendChild(a);

      /*for each item in the array...*/
      for (i = 0; i < places.length; i++) {
        var place = places[i];
        var countryId = place.CountryId.split('-')[0]; // AR-sky
        var countryName = place.CountryName; // Argentina
        var placeId = place.PlaceId.split('-')[0]; // SLA-sky
        var placeName = place.PlaceName; // Ciudad de Salta

        /*check if the item starts with the same letters as the text field value:*/

        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        if (placeName.substr(0, query.length).toUpperCase() == query.toUpperCase()) {
          b.innerHTML = "<strong>" + placeName.substr(0, query.length) + "</strong>";
          b.innerHTML += placeName.substr(query.length);
        } else {
          b.innerHTML = placeName;
        }
        b.innerHTML += ' - ' + '<small>' + countryName + '</small>';
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + placeId + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inp.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            closeAllLists();
        });
        a.appendChild(b);

      }
    });
    oReq.open("GET", "/api/autosuggest/AR/ARS/es-ES/" + query);
    oReq.send();
  }, 250);

  inp.addEventListener("input", debouncedSearch);
  
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}
var countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia","Turkey","Turkmenistan","Turks &amp; Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];
autocomplete(document.getElementById("flights-search-origin"), 'origin');
autocomplete(document.getElementById("flights-search-destination"), 'destination');

function handleSubmit(e) {
  e.preventDefault();
  console.log(e);

  window.location.href = '/vuelos/EZE/MIA/2020-10-13/2020-10-28';
};

document.getElementById("flights-search-form").addEventListener("submit", handleSubmit);
</script>