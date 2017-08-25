/* 
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */
$(document).ready(function () {
   $.datepicker.setDefaults({
        showOtherMonths: true,
        selectOtherMonths: true,
        dayNamesMin: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 
            'Июль', 'Август', 'Сентябрьr', 'Октябрь', 'Ноябрь', 'Декабрь'],
        dateFormat: "yy-mm-dd"

});
    $("#de").datepicker();
    $("#ds").datepicker();

});
