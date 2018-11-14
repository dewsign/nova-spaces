{{-- {{ dd($space) }} --}}
<div>
    @if($spaceLabel = $space->label)
        <h3>{{ $spaceLabel }}</h3>
    @endif

    @spaces($space->spaces)
</div>
