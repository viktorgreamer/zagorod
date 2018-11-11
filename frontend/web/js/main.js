$(".fill-stage").on('click', function (e) {
    var smeta_id = $(this).data('smeta_id');
    var stage_id = $(this).data('stage_id');
    console.log(smeta_id);
    console.log(this.class);
    $("#div-fill-stages").load($(this).attr('value'));

});

$(document).on('change', 'input', function () {
    console.log("INPUT WAS CHANGED");
    if ($(this).attr('type') == 'checkbox') {
        if ($(this).is(':checked')) {
            value = 1;
            window[$(this).attr('name')] = 1;
        } else {
            value = 0;
            window[$(this).attr('name')] = 0;
        }
        console.log(" name = " +  $(this).attr('name') + " value = " + value);
    } else {
        console.log(" name = " +  $(this).attr('name') + " value = " + $(this).val());

        value = $(this).val();

        if (isNumber(value)) {
            if (parseInt(value) == value) value = parseInt(value);
            else  value = parseFloat(value);
        } else value = value;

        window[$(this).attr('name')] = value;
    }


    // console.log("INPUT_2_ = " + input_2_);
    reload_events($(this).attr('name'));
});
$(document).on('change', 'select', function () {
    console.log("SELECT WAS CHANGED");
    if ($(this).attr('type') == 'checkbox') {
        if ($(this).is(':checked')) {
            value = 1;
            window[$(this).attr('name')] = 1;
        } else {
            value = 0;
            window[$(this).attr('name')] = 0;
        }
        console.log(" name = " +  $(this).attr('name') + " value = " + value);
    } else {
        console.log(" name = " +  $(this).attr('name') + " value = " + $(this).val());

        value = $(this).val();

        if (isNumber(value)) {
            if (parseInt(value) == value) value = parseInt(value);
            else  value = parseFloat(value);
        } else value = value;

        window[$(this).attr('name')] = value;
    }


    // console.log("INPUT_2_ = " + input_2_);
    reload_events($(this).attr('name'));
});


function control_inputs(event_id) {
    window.current_event_event =  event_id;


    console.log('СОБЫТИЕ ' + window['event_' + event_id + '_']);

    if (window['event_' + event_id + '_'] == 1) {
        window.input_controls.forEach(function(item) {
            console.log('ТЕКУЩИЙ ТИП' + item.type);

            if (item.event_id == window.current_event_event) {
                if (item.type == 1) {
                    $("#input_" + item.input_id + '_').val(item.value);

                }
                if (item.type == 2) {
                    $("#input_" + item.input_id + '_').attr('disabled',true);

                }
                if (item.type == 3) {
                    document.getElementById("input_" + item.input_id + '_').checked = true;

                }
                if (item.type == 4) {
                    document.getElementById("input_" + item.input_id + '_').checked = false;
                }
            }
        });
    } else {
        input_controls.forEach(function(item) {
            console.log('ТЕКУЩИЙ ТИП' + item.type);
            if (item.event_id == window.current_event_event) {

                if (item.type == 2) {
                    $("#input_" + item.input_id + '_').attr('disabled',false);

                }

                if (item.type == 3) {
                    console.log("REMOVING ATTREBUTE CHECKED");
                    document.getElementById("input_" + item.input_id + '_').checked = false;

                }
                if (item.type == 4) {
                    document.getElementById("input_" + item.input_id + '_').checked = true;


                }

            }
        });
    }

}


function reload_events(name = '') {

    if (name) {
        console.log(" RELOADING EVENTS ASSOCIATED WITH INPUT NAME = " + name);
        filtered_events = events.filter( function(event) {
            formula = event.formula;
            //  console.log('EVENT FORMULA = ' + event.formula);
            return formula.match(name)});

        filtered_events.forEach(function (item) {
            console.log(" EVENT ID = " + item.id + " FORMULA = (" + item.formula + ")");
            check_event(item);
        });

    } else {

        events.forEach(function (item) {
            check_event(item);

            //  console.log(" RESULT IS " + result);
        });

    }




    relatives_enents = events.filter( function(event) {
        formula = event.formula;
        // console.log('EVENT FORMULA = ' + event.formula);
        return formula.match(/event/)});

    //  console.log(" TRY TO CALCULATE EVENT RELATIVE ");

    relatives_enents.forEach(function (item) {
        check_event(item);
    });

}




function reload_inputs() {
    inputs = document.querySelectorAll('input');
    if (inputs) {
        inputs.forEach(function (item) {
            if (isNumber(item.value)) {
                if (parseInt(item.value) == item.value) window[item.name] = parseInt(item.value);
                else  window[item.name] = parseFloat(item.value);
            } else window[item.name] = item.value;
            console.log(" SETTING " + item.name + " VALUE " + item.value);
        })
    }
    inputs = document.querySelectorAll('select');
    if (inputs) {
        inputs.forEach(function (item) {
            if (isNumber(item.value)) {
                if (parseInt(item.value) == item.value) window[item.name] = parseInt(item.value);
                else  window[item.name] = parseFloat(item.value);
            } else window[item.name] = item.value;
            console.log(" SETTING " + item.name + " VALUE " + item.value);
        })
    }

}

function isNumber(value) {
    if ((undefined === value) || (null === value)) {
        return false;
    }
    if (typeof value == 'number') {
        return true;
    }
    return !isNaN(value - 0);
}

function check_event(item) {

    old_value = window['event_' + item.id + '_'];
    result = eval("(" + item.formula + ")");
    if (result == true) window['event_' + item.id + '_'] = 1;
    if (result == 0) window['event_' + item.id + '_'] = 0;
    if (result == false) window['event_' + item.id + '_'] = 0;
    if (old_value != window['event_' + item.id + '_']) {
        window.current_type = item.type;
        window.current_value = window['event_' + item.id + '_'];
        console.log(" EVENT ID = " + item.id  + " HAS CHANGED");
        event_nodes(item.id);
        control_inputs(item.id);
    }
    else console.log(" EVENT ID = " + item.id  + " HAS NOT CHANGED");
}


function event_nodes(event_id) {

    nodelists =  document.querySelectorAll("[data-event_id='" + event_id + "']");

    if (nodelists) {
        //  console.log(nodelists);

        nodelists.forEach( function(item)  {
            //  console.log(item.tagName);

            console.log(" CURRENT TYPE = " + window.current_type);
            console.log(" CURRENT VALUE = " + window.current_value);

            if (window.current_type == 'show') {
                if (window.current_value == 1) {
                    item.classList.add('active_element');
                    item.classList.remove('hidden');
                }
                else {
                    item.classList.remove('active_element');
                    item.classList.add('hidden');
                }
            } else {
                if (window.current_value == 0) {
                    item.classList.remove('active_element');
                    item.classList.add('hidden');
                }
                else {
                    item.classList.add('active_element');
                    item.classList.remove('hidden');
                }
            }


        });
    }

}





function get_input_value(input_id) {
    return document.querySelector('[name=' + input_id + ']').value;
}

function isChecked(input_id) {
    return document.getElementById(input_id).checked;
}

function findEventById(id,events) {

}
