import "../main";
import "../../app/plugins/controls/coordinates-input";

$(document).ready(function () {
    $('#form_data_coordinates').coordinatesInput({
        addressInput: '#form_data_address'
    });
});
