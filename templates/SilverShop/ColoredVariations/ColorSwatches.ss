<div class="silvershop-color-swatches">
    <p class="silvershop-color-swatches__heading">Available colors</p>

    <% loop Colors %>
        <div class="silvershop-color-swatches__swatch" style="background-color: #$Color;">
            <span class="silvershop-color-swatches__value">$Value</span>
        </div>
        <div class="silvershop-color-swatches__images">
            <% if ColorImages %>
                <% loop ColorImages %>
                    $Me
                <% end_loop %>
            <% end_if %>
        </div>
    <% end_loop %>
</div>
