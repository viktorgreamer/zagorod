function copyToClipboard(element) {
    $('#clipboard_area').text(element);
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($('#clipboard_area').text()).select();
    document.execCommand("copy");
    $temp.remove();
    toastr.success(element, 'Скопировано в буфер');
}

function toggleSelectedCells(tr_id, td_id) {
    if (selectedCellsArray = window.selectedCells) {
        selectedCellsArray.forEach(function (item, index) {
            if ((item[1] == tr_id) && (item[1] == td_id)) {
                window.selectedCells.splice(index, 1)

            }
        });
    } else {
        console.log(" SELECTED CELLS IF EMPTY");
        window.selectedCells.push([tr_id, td_id]);
    }

    console.log(window.selectedCells);
}


$(document).on('click', 'button.modal-button-create-estimate-stage', function (e) {
    var estimate_id = $(this).data('estimate_id');
    console.log(estimate_id);
    console.log("button.modal-button-create-estimate-stage-ajax");
    $("#modal-estimate-id-" + estimate_id).modal('show').find("#modal-estimate-div-id-" + estimate_id).load($(this).attr('value'));

});

$(document).on('click', 'button.modal-button-update-estimate-stage', function (e) {
    var estimate_id = $(this).data('estimate_id');
    console.log("button.modal-button-update-estimate-stage");
    $("#modal-estimate-id-" + estimate_id).modal('show').find("#modal-estimate-div-id-" + estimate_id).load($(this).attr('value'));

});

// открытие модальных окон добавления/редактирования входных данных
$(document).on('click', '.modal-button-create-stage-input-ajax', function (e) {
    var stage_id = $(this).data('stage_id');
    console.log(stage_id);
    console.log("button.modal-button-create-stage-input-ajax");
    $("#modal-input-ajax-id-" + stage_id).modal('show').find("#div-create-stage-input-ajax-stage-id-" + stage_id).load($(this).attr('value'));

});
$(document).on('click', 'button.modal-button-update-stage-input-ajax', function (e) {
    var stage_id = $(this).data('stage_id');
    console.log("button.modal-button-update-stage-input-ajax");
    $("#modal-input-ajax-id-" + stage_id).modal('show').find("#div-create-stage-input-ajax-stage-id-" + stage_id).load($(this).attr('value'));

});

// открытие модальных окон добавления/редактирования выходных данных
$(document).on('click', 'button.modal-button-create-stage-output-ajax', function (e) {
    var stage_id = $(this).data('stage_id');
    console.log(stage_id);
    console.log("button.modal-button-create-stage-input-ajax");
    $("#div-create-stage-output-ajax-stage-id-" + stage_id).load($(this).attr('value'));
});


$(document).on('click', 'button.modal-button-update-stage-output-ajax', function (e) {
    var output_id = $(this).data('output_id');
    console.log("button.modal-button-update-stage-output-ajax");
    $("#div-update-stage-output-ajax-output-id-" + output_id).load($(this).attr('value'));

});


$(document).on('click', '.delete-stage', function (e) {
    var stage_id = $(this).data('stage_id');
    console.log("deleting-stage-id" + stage_id);
    $("#div-stage-id-" + stage_id).remove();

    var status_name = $(this).data('status_name');
    var status = $(this).data('status');

    $.ajax({
        url: '/admin/estimate-stage/change-status',
        data: {stage_id: stage_id, status_name: status_name, status: status},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled = true;


});

// изменение проритета входящих данных

$(document).on('click', '.input-priority-change', function (e) {
    var input_id = $(this).data('input_id');
    var priority = $(this).data('priority');
    console.log("input-priority-change" + input_id + priority);


    $.ajax({
        url: '/admin/input/change-priority',
        data: {input_id: input_id, priority: priority},
        type: 'get',
        success: function (res) {

            $.pjax.reload('#pjax_id', {timeout: false});

        },

        error: function () {
            alert('error')
        }
    });


    this.disabled = true;


});

// изменение проритета входящих данных
$(document).on('click', '.copy-input-to-output', function (e) {
    var input_id = $(this).data('input_id');

    console.log("copy-input-to-output" + input_id);


    $.ajax({
        url: '/admin/input/copy-to-output',
        data: {input_id: input_id},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });
    this.disabled = true;


});

// удаление input
$(document).on('click', '.delete-input', function (e) {
    if (confirm('Вы уверены, что хотите удалить поле ввода ?')) {
        var input_id = $(this).data('input_id');

        $.ajax({
            url: '/admin/input/delete-ajax?id=' + input_id,
            // data: {id: input_id},
            type: 'post',
            complete: function (res) {
                console.log("RES  = " + res);
                if (res) {

                    $("#div-input-id-" + input_id).addClass('hidden');
                }
            },


        });
        this.disabled = true;
    }


});

// удаление output
$(document).on('click', '.delete-output', function (e) {
    if (confirm('Вы уверены, что хотите удалить поле вывода ?')) {
        var output_id = $(this).data('output_id');

        $.ajax({
            url: '/admin/output/delete-ajax',
            data: {id: output_id},
            type: 'post',
            complete: function (res) {
                console.log("RES  = " + res);
                if (res) {

                    $("#div-output-id-" + output_id).addClass('hidden');
                }
            },


        });
        this.disabled = true;
    }


});


// изменение проритета исходящих данных
$(document).on('click', '.output-priority-change', function (e) {
    var output_id = $(this).data('output_id');
    var priority = $(this).data('priority');
    console.log("ioutput-priority-change" + output + priority);


    $.ajax({
        url: '/admin/output/change-priority',
        data: {output_id: output_id, priority: priority},
        type: 'get',
        success: function (res) {
            $.pjax.reload('#pjax_id', {timeout: false});
        },

        error: function () {
            alert('error')
        }
    });
    this.disabled = true;


});

$(document).on('click', '.stage-priority-change', function (e) {
    var stage_id = $(this).data('stage_id');
    var priority = $(this).data('priority');
    console.log("stage-priority-change" + stage_id + priority);


    $.ajax({
        url: '/admin/estimate-stage/change-priority',
        data: {stage_id: stage_id, priority: priority},
        type: 'get',
        success: function (res) {
            $.pjax.reload('#pjax_id', {timeout: false});
        },

        error: function () {
            alert('error')
        }
    });
    this.disabled = true;


});


$(".add-base-stations-to-group").on('click', function (e) {
    var group_id = $(this).data('group_id');

    $.ajax({
        url: '/admin/base-station-group/add-stations',
        data: {group_id: group_id},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });

    console.log('SUCCESS add-base-stations-to-group');


});

$(".delete-material-from-group").on('click', function (e) {
    var id = $(this).data('id');

    $.ajax({
        url: '/admin/base-station-group/delete-material-from-group',
        data: {id: id},
        type: 'get',
        success: function (res) {

        },

        /* error: function () {
             alert('error')
         }*/
    });

    console.log('SUCCESS add-base-stations-to-group');


});
$(".add-base-stations-to-group").on('click', function (e) {
    var group_id = $(this).data('group_id');

    $.ajax({
        url: '/admin/base-station-group/add-stations',
        data: {group_id: group_id},
        type: 'get',
        success: function (res) {

        },

        error: function () {
            alert('error')
        }
    });

    console.log('SUCCESS add-base-stations-to-group');


});


$('.change-status-stage').on('click', function (e) {
    e.preventDefault();

});

// добавлание материала к группе
$("button.add-material-group").on('click', function (e) {
    console.log("button.add-material-group PRESSED");
    $("#add-material-to-group-from").load($(this).attr('value'));

});


// удалепние материала из группы
$(".delete-material-from-group").on('click', function (e) {
    var id = $(this).data('id');

    $.ajax({
        url: '/admin/base-station-group/delete-material-from-group',
        data: {id: id},
        type: 'get',
        success: function (res) {

        },

        /* error: function () {
             alert('error')
         }*/
    });

    console.log('SUCCESS add-base-stations-to-group');


});


// добавление материала к станции
$("button.add-material-station").on('click', function (e) {
    console.log("button.station PRESSED");
    $("#add-material-to-station-div").load($(this).attr('value'));

});


// удалепние материала из станции
$(".delete-material-from-station").on('click', function (e) {
    var id = $(this).data('id');

    $.ajax({
        url: '/admin/base-station/delete-material-from-station',
        data: {id: id},
        type: 'get',
        success: function (res) {

        },

        /* error: function () {
             alert('error')
         }*/
    });

});




