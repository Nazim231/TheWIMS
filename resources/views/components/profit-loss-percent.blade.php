<div class="{{ $type }}">
    <img src="{{ asset('images/icons/ic-' . $type . '.svg') }}" alt="">
    <span>{{ ($type == 'profit' ? '+' : '') . $value . '% Last ' . $period }}</span>
</div>
