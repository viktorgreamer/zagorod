$(".fill-stage").on('click', function (e) {
    var smeta_id = $(this).data('smeta_id');
    var stage_id = $(this).data('stage_id');
    console.log(smeta_id);
    console.log(this.class);
    $("#div-fill-stages").load($(this).attr('value'));

});




function get_input_value(input_id) {
    return document.querySelector('[name=' + input_id + ']').value;
}

function isChecked(input_id) {
    return document.getElementById(input_id).checked;
}

function findEventById(id,events) {

}
