station = {"id":"922","articul":"#\u041d\/\u0414","name":"\u0421\u0442\u0430\u043d\u0446\u0438\u044f \u0415\u0432\u0440\u043e\u043b\u043e\u0441 \u0411\u0418\u041e 5+ (\u043f\u0440) \u0441 \u0434\u043e\u043f. \u0433\u043e\u0440\u043b\u043e\u0432\u0438\u043d\u043e\u0439 40\u0441\u043c","measure":"0","count":"1","price":"79500","cost":"79500","mark":"\u0415\u0432\u0440\u043e\u043b\u043e\u0441 \u0411\u0418\u041e","performance":"0","people":"5","fecal_nas":"0","sp":"0","deep":"910","type_calculate_id":"10","self_cost":"55650","montaj":"17000","pnr":"7500","rshm":"12500","yakor":"0","length":"1.40","width":"1.40","height":"2.40","utepl":"4","water":"2.30","sand_manual":"4.73","sand_tech":"8.89","cement_manual":"515.94","cement_manual_pac":"11","cement_tech":"969.76","cement_tech_pac":"20","pit_manual":"8.42","pit_tech":"12.58","gasket":"500","with_chasers":""};
events = [{id:10,name:'Выбран фильтрующий колодец',formula:
            "input_49_",value:1,type:'show'},{id:11,name:'Выбран сборный колодец',formula:
            "input_65_",value:1,type:'show'},{id:13,name:'Выбрана КНС',formula:
            "input_91_",value:1,type:'show'},{id:14,name:'Выбрана КНС с блоком управления',formula:
            "input_95_",value:1,type:'show'},{id:15,name:'Грунтовые воды точное значение',formula:
            "(input_112_  == 'Значение')",value:1,type:'show'},{id:4,name:'Выбран Подкоп под ленту фундамента',formula:
            "input_53_",value:1,type:'show'},{id:5,name:' выбрано Переделка (поднятие) вывода',formula:
            "input_55_",value:1,type:'show'},{id:6,name:'Выбран Проход ж\б кольца',formula:
            "input_57_",value:1,type:'show'},{id:7,name:'Выбрана Гильза',formula:
            "input_59_",value:1,type:'show'},{id:16,name:'Выбрана подрубка корней',formula:
            "input_117_",value:1,type:'show'},{id:17,name:'Выбрана планировка',formula:
            "(input_116_ == 'Планировка')",value:1,type:'show'},{id:12,name:'Выбран блок управления к сборному колодцу',formula:
            "input_83_",value:1,type:'show'},{id:18,name:'Нужен шланг',formula:
            "input_127_",value:1,type:'show'},{id:19,name:'Вода есть на участке',formula:
            "(input_123_  == 'На участке')",value:0,type:'show'},{id:20,name:'Есть вывод из дома',formula:
            "(input_50_  != 'Нет')",value:1,type:'hide'},{id:21,name:'Есть перемещение грунта',formula:
            "input_131_",value:0,type:'show'},{id:22,name:'Есть демонтаж колец',formula:
            "input_135_",value:0,type:'show'},{id:24,name:'Есть перемещение демонтированных колец',formula:
            "input_137_",value:0,type:'show'},{id:25,name:'Выбрана СТАНЦИЯ П',formula:
            "(station.sp == 1)",value:0,type:'show'},{id:26,name:'Копка канавы',formula:
            "input_145_",value:1,type:'show'},{id:27,name:'Срабатываю если значение больше 9',formula:
            "((input_156_ + input_157_) > 9)",value:0,type:'show'}];
var input_controls = [{event_id: 27,input_id: 159,type: 1,value: 3},{event_id: 27,input_id: 159,type: 2,value: 0}];
console.log(input_controls);

true_events = events.filter( function(item) {
    return (item.value == 1)
});

console.log(true_events);

    
    
 // определение переменных
    events.forEach(function (item) {
        window['event_' + item.id + '_'] = item.value;
      //  console.log('event_' + item.id + '_' + '=' + item.value);
    });

    reload_inputs();
    reload_events();
    
    
    console.log(true_events);
    


$(document).on('change', 'input', function () {
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
        input_controls.forEach(function(item) {
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

