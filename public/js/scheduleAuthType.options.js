/* 
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */
$(document).ready(function () {

    $("#de").datepicker();
    $("#ds").datepicker();

    $("#authType-1").change(function () {
        document.getElementById("pass").style.display = 'block';
        document.getElementById("password").required = true;
    });

    $("#authType-2").change(function () {
        document.getElementById("pass").style.display = 'none';
        document.getElementById("password").value = '';
        document.getElementById("password").required = false;

    });

    var type1 = $("#authType-1").prop("checked");
    if (type1 === true) {
        document.getElementById("pass").style.display = 'block';
        document.getElementById("password").required = true;
    }
});
