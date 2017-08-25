/* 
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * 
 */

$(document).ready(function () {
    $('#dataTable').DataTable({
        stateSave: true,
        "order": [[0, "desc"]],
        "columnDefs": [{
                "targets": 'noSort',
                "searchable": false,
                "orderable": false
            }],
        "iDisplayLength": 10,
        "language": {
            "info": "",
            "zeroRecords": "Не найдено записей ",
            "search": "Поиск :",
            "infoFiltered": "Отфильтровано из _MAX_ записей",
            "lengthMenu": "Показать _MENU_ записей",
            "infoEmpty": "",
            "processing": "Загрузка...",
            "paginate": {
                "previous": "Назад",
                "next": "Вперед",
            }
        },
        "initComplete": function () {
            this.api().columns().every(function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val =
                                    $(this).val();


                            column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                        });

                column.data().unique().sort().each(function (d, j) {
                    if (column.search() === '^' + d + '$') {
                        select.append('<option value="' + d + '" selected="selected">' + d + '</option>')
                    } else {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    }
                });
            });
        }
    });
});
