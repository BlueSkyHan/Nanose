@if($paginator->hasPages())
<ul id="salesOrder-members-pagination" class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
    @else
        <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fa fa-angle-left"></i></a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <li class="disabled"><span>{{ $element }}</span></li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active"><span>{{ $page }}</span></li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa fa-angle-right"></i></a></li>
    @else
        <li class="disabled"><span><i class="fa fa-angle-right"></i></span></li>
    @endif
</ul>
@endif

<script>
    $(function(){
        var dateRangePicker = $('.daterangepicker');
        if(dateRangePicker.length){
            dateRangePicker.remove();
        }

        $(document).off('input', 'input');

        $('ul#salesOrder-members-pagination li a').click(function(e){
            e.preventDefault();
            $.get($(this).prop('href'), function(data){
                $('#salesOrder-members-container').html(data);
            });
        });
    });
</script>

<style>
    .pagination{
        margin : 0 !important;
    }

    .pagination .active a,
    .pagination .active a:focus,
    .pagination .active a:hover,
    .pagination .active span,
    .pagination .active span:focus,
    .pagination .active span:hover{
        padding: 0px 5px !important;
        color: #00a65a !important;
        background-color: transparent !important;
        border-color: transparent !important;
    }

    .pagination a,
    .pagination a:focus,
    .pagination a:hover,
    .pagination span,
    .pagination span:focus,
    .pagination span:hover{
        padding: 0px 5px !important;
        color: #4e4e4e !important;
        background-color: transparent !important;
        border-color: transparent !important;
    }
</style>