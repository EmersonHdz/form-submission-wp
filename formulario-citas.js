document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    var formulario = document.querySelector('.formulario-citas');
    if (formulario) {
        validarFecha();
        validarHora();
    }
}



function validarFecha(){
    var fechaInput = document.getElementById('fecha');
    fechaInput.addEventListener('input', function(){
        var fechaSeleccionada = new Date(this.value);
        if(fechaSeleccionada.getDay() === 0 ){
            this.setCustomValidity('Appointments cannot be selected for Sundays.');
        } else {
            this.setCustomValidity('');
        }
    
    });

}


function validarHora(){
    var horaInput = document.getElementById('hora');
    horaInput.addEventListener('input', function(e){
        const HoraCita = e.target.value;
        const hora = HoraCita.split(':')[0];
        if( hora < 8 || hora >= 18){
            e.target.setCustomValidity('The time must be between 8:00 and 17:59');
        } else{
            e.target.setCustomValidity('');
        }
    });
}

