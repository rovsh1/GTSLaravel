import "../main";
import "../../plugins/jquery/coordinates-input";

$(document).ready(function () {
    $('#form_data_coordinates').coordinatesInput({
        addressInput: '#form_data_address'
    });
});
