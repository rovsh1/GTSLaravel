import "../main";
import "./coordinates";
import {initGoogleMapsScript} from "../../plugins/map/google";

initGoogleMapsScript()

$(document).ready(function (){
    $('#form_data_address').coordinates();
});
