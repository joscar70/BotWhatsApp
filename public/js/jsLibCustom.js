const modeSpa=false;

$(document).ready(function(){
  startTime()
})

$(document).on("click", ".opMenu", function(e){
    e.preventDefault();
    activaSpaStateP($(this).attr('pagina'))
})

function activaSpaStateP(startView){
    if(modeSpa){
        var History = window.History; 
        //appStart();
        //
        if (History.enabled) {
          //console.log("History.enabled", History.enabled);
          var page = get_url_value('page');
          var urlPath = page ? page : ruta+"/"+startView; // opciones = Contenido Inicial

          loadWrapperP(urlPath);
          History.pushState({path: urlPath}, 'Workmed', urlPath);
        } else {
          return false;
        }

         History.Adapter.bind(window, 'statechange', function() {
          var State = History.getState(); 
           loadWrapperP(State.data.path + "/"); 
           //console.log("State.data.path", State.data.path);
        }); 
        //console.log("spa", modeSpa);
     }else{
        loadWrapperP(startView);
    //     //console.log("spa", modeSpa);
    }
}

function loadWrapperP(vista){
  $("#wrapperDegresP").empty();
  $('#wrapperDegresP').hide('blind');
  $('#wrapperDegresP').load(vista,
  function(response, status, xhr) {
    if ( status == "error" ) {
       toastr.warning('Problemas técnicos ocurrieron, envienos un email para ayudarlo en su trámite')
    }else{
        $(".nav-treeview").hide('blind');
        $(".menu-open").removeClass("menu-open");
        $('#wrapperDegresP').show('blind');

    }
      
  });
};

/** Relog **/

function startTime() {
    var today = new Date();
    var hr = today.getHours();
    var min = today.getMinutes();
    var sec = today.getSeconds();
    // ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
    // hr = (hr == 0) ? 12 : hr;
    // hr = (hr > 12) ? hr - 12 : hr;
    // //Add a zero in front of numbers<10
    // hr = checkTime(hr);
    // min = checkTime(min);
    // sec = checkTime(sec);
    hora = hr + ":" + min;
    document.getElementById("clock").innerHTML = hr + ":" + min + ":" + sec;// + " " + ap;
    
    var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    var days = ['Dom', 'Lun', 'Mar', 'Mie', 'jue', 'Vie', 'Sab'];
    var curWeekDay = days[today.getDay()];
    var curDay = today.getDate();
    var curMonth = months[today.getMonth()];
    var curYear = today.getFullYear();
    var date = curWeekDay+", "+curDay+" "+curMonth+" "+curYear;
    document.getElementById("date").innerHTML = date;
    
    var time = setTimeout(function(){ startTime() }, 500);
}