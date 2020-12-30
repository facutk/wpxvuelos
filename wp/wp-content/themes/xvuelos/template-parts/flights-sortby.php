<div class="d-flex mb-3" role="group" aria-label="Basic example">
  <span class="text-nowrap mr-2 py-3">Ordenar por</span>
  <select
    name="sortby"
    class="w-100 p-3 border-grey"
    style="border-color: #d6d7df"
    form="flights-search-form'"
    onchange="handleSubmit(event)"
  >
    <option value="best">Mejor opción</option>
    <option value="fast">Más rápido</option>
    <option value="price">Mejor precio</option>
  </select>
</div>