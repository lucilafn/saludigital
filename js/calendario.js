console.log("calendario.js cargado");

// Este script inicializa el calendario y pide los eventos al archivo PHP

document.addEventListener('DOMContentLoaded', function () {

  console.log("Iniciando FullCalendar...");
  // Seleccionamos el contenedor del calendario
  let calendarEl = document.getElementById('calendar');

  // Creamos el calendario
  let calendar = new FullCalendar.Calendar(calendarEl, {
    // locale: 'esLocale', // idioma español
    initialView: 'dayGridMonth', // vista inicial (mes)

    // Cargamos los eventos desde nuestro PHP
    events: '/saludigital/saludigital/php/usuario/eventoscalendario.php',

    eventTimeFormat: { // like '14:30:00'
    hour: '2-digit',
    minute: '2-digit',
    meridiem: 'short'
    },

    // Cuando hago clic en un evento, muestro un popup con más detalles
    eventClick: function(info) {
      swal(
        "Doctor: " + info.event.extendedProps.doctor + "\n",
        "Servicio: " + info.event.extendedProps.servicio + "\n" +
        "Horario: " + info.event.start.toLocaleString()
      );
    }
  });                           

  // Renderizamos el calendario para que se vea en pantalla
  calendar.render();
});
