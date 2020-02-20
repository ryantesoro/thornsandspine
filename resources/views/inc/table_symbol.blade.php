@if (Request::input('sort') == 0)
<i class="fas fa-sort-down ml-1"></i>
@elseif (Request::input('sort') == 1)
<i class="fas fa-sort-up ml-1"></i>
@endif