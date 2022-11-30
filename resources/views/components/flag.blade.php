@if (isset($address->country->code))
    <span style="display: inline-flex; justify-content: space-around;">
        <img src="https://raw.githubusercontent.com/DvDty/node-track/1.0/public/images/countriesv2/{{ strtolower($address->country->code) }}.svg"
             class="img-fluid"
             alt="country flag"
             style="max-width: 35px; border: 1px solid dimgrey;">
    </span>
@endif
