/* 
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 * parent_id  --- селект по которому выбираем
 * child_id   --- селект который выбираем по parent_id
 */

$(document).ready(function () {
    $('select[id][data-type~="parent"]').change(function () {
        var parent_id = $(this).val();
        var child_id = $('select[id][data-type="child"]');

        if (parent_id == '') {
            child_id.html('<option>Выберите ..</option>');
            child_id.attr('disabled', true);
            return(false);
        }
        child_id.attr('disabled', true);
        child_id.html('<option>Выберите ..</option>');

//        var url = '/question/select';
        if (typeof urlRedirectSelect === "undefined") {
            alert('Произошла ошибка, не указан адрес для выборки');
        }
        $.get(
                urlRedirectSelect,
                "parent_id=" + parent_id,
                function (result) {
                    if (result.type === 'error') {
                        alert("При выполнении запроса произошла ошибка :(");
                        return(false);
                    } else {
                        var options = '<option value="">Выберите..</option>';
                        $(result).each(function () {
                            options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('fname') + '</option>';
                        });
                        child_id.html(options);
                        child_id.attr('disabled', false);
                   
                    }
                },
                "json"
                );
    });
});


